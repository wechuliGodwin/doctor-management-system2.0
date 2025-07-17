<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\PharmacyLoginController;
use App\Http\Controllers\Auth\PharmacyRegisterController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingAuthController;
use App\Http\Controllers\BookingDoctorController;
use App\Http\Controllers\BulkEmailManagerController;
use App\Http\Controllers\BulkMailController;
use App\Http\Controllers\ChaplaincyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiseaseController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InformationPackController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\OfferLetterController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientMeetingController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicationsController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\ResearchController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ShortCoursesController;
use App\Http\Controllers\SimulationController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\SmsIntergrationController;
use App\Http\Controllers\StaffAuthController;
use App\Http\Controllers\SuperAdmin\UserManagementController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\UpdatesController;
use App\Http\Controllers\VisitingLearnerController;
use App\Http\Livewire\DoctorDashboard;
use App\Livewire\Spdmin;
use App\Livewire\Superadmin;
use Illuminate\Foundation\Auth\EmailVerificationNotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

// Survey Routes
Route::get('/emr-benchmarking', [SurveyController::class, 'showEmailForm'])->name('emr.benchmarking.form');
Route::post('/emr-benchmarking', [SurveyController::class, 'validateEmail'])->name('emr.benchmarking.validate');
Route::get('/emr-benchmarking/page', [SurveyController::class, 'showBenchmarkingPage'])->name('emr.benchmarking.page');
Route::get('/emr-benchmarking/objectives', [SurveyController::class, 'showObjectives'])->name('emr.objectives.list');
Route::get('/emr-benchmarking/features', [SurveyController::class, 'showFeatures'])->name('emr.features.list');
Route::get('/erp-lifecycle', [SurveyController::class, 'showErpLifecycle'])->name('erp.lifecycle');
Route::post('/emr-benchmarking/objective/submit', [SurveyController::class, 'submitObjective'])->name('emr.benchmarking.objective.submit');
Route::post('/emr-benchmarking/feature/submit', [SurveyController::class, 'submitFeature'])->name('emr.benchmarking.feature.submit');
Route::get('/survey', [SurveyController::class, 'show'])->name('survey.show');
Route::post('/survey/submit', [SurveyController::class, 'submit'])->name('survey.submit');

// Pharmacy Authentication Routes
Route::get('/pharmacy/login', [PharmacyLoginController::class, 'showLoginForm'])->name('pharmacy.login');
Route::post('/pharmacy/login', [PharmacyLoginController::class, 'login']);
Route::get('/pharmacy/register', [PharmacyRegisterController::class, 'showPharmacyRegistrationForm'])->name('pharmacy.register');
Route::post('/pharmacy/register', [PharmacyRegisterController::class, 'register']);

// Authenticated Pharmacy Routes
Route::middleware('auth')->group(function () {
    Route::get('/services/pharmacy', [PharmacyController::class, 'index'])->name('pharmacy.index');
    Route::post('/pharmacy/order/store', [PharmacyController::class, 'storeOrder'])->name('pharmacy.order.store');
    Route::post('/pharmacy/consultation/store', [PharmacyController::class, 'storeConsultation'])->name('pharmacy.consultation.store');
    Route::post('/pharmacy/refill/store', [PharmacyController::class, 'storeRefill'])->name('pharmacy.refill.store');
    Route::get('/pharmacist', [PharmacyController::class, 'pharmacistIndex'])->name('pharmacist.index');
    Route::patch('/pharmacist/order/{id}/approve', [PharmacyController::class, 'approveOrder'])->name('pharmacist.order.approve');
    Route::patch('/pharmacist/order/{id}/reject', [PharmacyController::class, 'rejectOrder'])->name('pharmacist.order.reject');
    Route::patch('/pharmacist/refill/{id}/approve', [PharmacyController::class, 'approveRefill'])->name('pharmacist.refill.approve');
    Route::patch('/pharmacist/refill/{id}/reject', [PharmacyController::class, 'approveRefill'])->name('pharmacist.refill.reject');
    Route::post('/pharmacy/otc/store', [PharmacyController::class, 'storeOtc'])->name('pharmacy.otc.store');
});

// Simulation Routes
Route::get('/simulation', [SimulationController::class, 'index'])->name('simulation');
Route::get('/simulation/register', [SimulationController::class, 'register'])->name('simulation.register');
Route::post('/simulation/submit-application', [SimulationController::class, 'submitApplication'])->name('simulation.submit-application');

//Care Service Areas
Route::get('/services/{care}', [ServiceController::class, 'showCareArea'])->name('services.care');

//Updates
Route::get('/updates', [UpdatesController::class, 'index'])->name('updates');

// Chaplaincy Route
Route::get('/chaplaincy', [ChaplaincyController::class, 'index'])->name('chaplaincy');

// Newsletter Routes
Route::get('/newsletter', [NewsletterController::class, 'showForm'])->name('newsletter.form');
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// Information Pack Route
Route::get('/information-pack', [PublicationsController::class, 'showInformationPack'])->name('information-pack');

// Research Home Route
Route::get('/education/research', [PublicationsController::class, 'show'])->name('research-home');

// Research Day Redirect
Route::get('/research-day', function () {
    return redirect()->route('education.research-day');
})->name('research-day');

// Consolidated Education Routes
Route::group(['prefix' => 'education'], function () {
    Route::get('/research-day', [PublicationsController::class, 'showResearchDay'])->name('education.research-day');
    Route::get('/register-research-day', [PublicationsController::class, 'registerResearchDay'])->name('register-research-day');
    Route::post('/submit-research-day-registration', [PublicationsController::class, 'submitResearchDayRegistration'])->name('submit-research-day-registration');
    Route::get('/view-research-day-registrations', [PublicationsController::class, 'viewResearchDayRegistrations'])->name('view-research-day-registrations');
    Route::get('/view-poster/{id}', [PublicationsController::class, 'viewPoster'])->name('view-poster');
    Route::post('/upload-research-poster', [PublicationsController::class, 'uploadPoster'])->name('upload.research.poster');
    Route::post('/upload-chunk', [PublicationsController::class, 'handleChunkUpload'])->name('upload.chunk');
    Route::post('/handle-dropzone-upload', [PublicationsController::class, 'handleDropzoneUpload'])->name('handle-dropzone-upload');
    Route::get('/view-research-day-posters', [PublicationsController::class, 'viewResearchDayPosters'])->name('view-research-day-posters');
    Route::get('/research-day-gallery', [PublicationsController::class, 'showResearchDayGallery'])->name('research-day-gallery');
    Route::get('/research-papers', [PublicationsController::class, 'index'])->name('research-papers');
    Route::get('/iserc', [ResearchController::class, 'showIsercForm'])->name('iserc');
    Route::post('/iserc/submit', [ResearchController::class, 'submitIsercForm'])->name('iserc.submit');
    Route::get('/short-courses-application', function () {
        return view('education.short-courses-application');
    })->name('short-courses-application');
    Route::post('/short-courses-application', [ShortCoursesController::class, 'submitApplication'])->name('submit.short-courses-application');
});

// Telemedicine Pharmacy Route
Route::get('/services/telemedicine-pharmacy', function () {
    return view('services.telemedicine-pharmacy');
})->name('telemedicine-pharmacy');

// Bulk Mail Routes
Route::get('/suppliers/bulkmail', [BulkMailController::class, 'index'])->name('suppliers.bulkmail');
Route::post('/suppliers/bulkmail/send', [BulkMailController::class, 'send'])->name('suppliers.bulkmail.send');
Route::post('/suppliers/bulkmail/code', [BulkMailController::class, 'checkCode'])->name('suppliers.bulkmail.code');

// Offer Letter Route
Route::get('/download-offer-letter/{supplier}', [OfferLetterController::class, 'download'])->name('download.offer.letter');

// Super Admin Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/superadmin/user-management', [UserManagementController::class, 'index'])->name('superadmin.user_management');
    Route::post('/superadmin/change-password', [UserManagementController::class, 'changePassword'])->name('superadmin.change_password');
    Route::post('/superadmin/change-role', [UserManagementController::class, 'changeRole'])->name('superadmin.change_role');
});

// Prescription Routes
Route::get('/prescription/{appointment_id}', [PrescriptionController::class, 'downloadPrescription']);
Route::get('/download-prescription/{appointment_id}', [PrescriptionController::class, 'downloadPrescription'])->name('download.prescription');
Route::post('/save-prescription', [PrescriptionController::class, 'savePrescription'])->name('save-prescription');

// Sitemap Routes
Route::get('/sitemap.xml', [SitemapController::class, 'index']);
Route::get('/sitemap', function () {
    return view('sitemap');
})->name('sitemap');

// Miscellaneous Public Routes
Route::get('/articles', function () {
    return view('articles');
})->name('articles');
Route::get('/leaders', function () {
    return view('leaders');
})->name('leaders');

// QR Code Generation Route
Route::post('/generate-qr', function (Request $request) {
    $request->validate(['url' => 'required|url']);
    $qrCode = base64_encode(QrCode::format('png')->size(200)->generate($request->url));
    return back()->with('qr_code', $qrCode);
})->name('generate.qr');

// Meeting and Room Routes
Route::get('/create-meeting/{appointment_id}', [MeetingController::class, 'createMeeting'])->name('create-meeting');
Route::get('/create-meeting', [MeetingController::class, 'index'])->name('meeting.create');
Route::get('/qrcode', function () {
    return view('qr-generator');
})->name('qrcode');
Route::get('/create-Roompatient', [PatientMeetingController::class, 'createMeeting'])->name('create-Roompatient');
Route::get('/create-Room', [RoomController::class, 'createMeeting'])->name('create-Room');
Route::view('/create-meeting', 'livewire.create-meeting');

// Feedback Routes
Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
Route::get('/feedback', [FeedbackController::class, 'create'])->name('feedback.create');

// Livewire Routes
Route::view('/dash', 'livewire.doctor-dashboard');
Route::view('/appointments', 'appointments');
Route::view('/Board', 'livewire.superadmin');
Route::view('/publications', 'publications');
Route::get('/superadmin', Spdmin::class)->name('livewire.superadmin');

// Appointment Update Route
Route::put('/appointments/update', [AppointmentController::class, 'update'])->name('appointments.update');

// Staff Authentication Routes
Route::get('/staff/login', [StaffAuthController::class, 'showLoginForm'])->name('staff.login');
Route::post('/staff/login', [StaffAuthController::class, 'login'])->name('staff.login.submit');
Route::post('/staff/logout', [StaffAuthController::class, 'logout'])->name('staff.logout');
Route::prefix('staff')->group(function () {
    Route::get('/forgot-password', [StaffAuthController::class, 'showLinkRequestForm'])->name('staff.password.request');
    Route::post('/forgot-password', [StaffAuthController::class, 'sendResetLinkEmail'])->name('staff.password.email');
    Route::get('/reset-password/{token}', [StaffAuthController::class, 'showResetForm'])->name('staff.password.reset');
    Route::post('/reset-password', [StaffAuthController::class, 'reset'])->name('staff.password.update');
});

// Telemedicine Redirect
Route::get('/go-to-telemedicine', [RedirectController::class, 'telemedicine'])->name('redirect.telemedicine');

// Staff Dashboard and Features
Route::middleware(['auth:staff'])->group(function () {
    Route::get('/staff/dashboard', function () {
        return view('staff.dashboard');
    })->name('staff.dashboard');
    Route::get('/staff/bulk-emails', [BulkEmailManagerController::class, 'index'])->name('staff.bulk-emails');
    Route::post('/staff/send-bulk-emails', [BulkEmailManagerController::class, 'sendBulkEmails'])->name('staff.send-bulk-emails');
    Route::get('/staff/blog', [BlogController::class, 'index'])->name('staff.blog.index');
    Route::get('/staff/blog/create', [BlogController::class, 'create'])->name('staff.blog.create');
    Route::post('/staff/blog', [BlogController::class, 'store'])->name('staff.blog.store');
    Route::get('/staff/blog/{blog}/edit', [BlogController::class, 'edit'])->name('staff.blog.edit');
});

// Disease Routes
Route::get('/diseases/{slug}', [DiseaseController::class, 'show'])->name('diseases.show');
Route::get('/diseases', [DiseaseController::class, 'index'])->name('diseases.index');
Route::get('/diseases/{id}', [DiseaseController::class, 'show'])->name('diseases.show');

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/blog/{id}', [HomeController::class, 'show'])->name('blog.show');
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Additional Education Routes
Route::get('/pathways', function () {
    return view('education.research-pathways');
})->name('pathways');
Route::get('/internships-application', function () {
    return view('internships-application');
})->name('internships-application');
Route::get('/more-research-day', function () {
    return view('education.more-research-day');
})->name('more-research-day');

// Visiting Learners Routes
Route::get('/visiting-learners', [VisitingLearnerController::class, 'create'])->name('visiting-learners.create');
Route::post('/visiting-learners', [VisitingLearnerController::class, 'store'])->name('visiting-learners.store');
Route::get('/visiting-learner-application', [VisitingLearnerController::class, 'index'])->name('visiting-learners-application');
Route::get('/visiting-learners', [VisitingLearnerController::class, 'index'])->name('visiting-learners.index');
Route::get('/visiting-learners/create', [VisitingLearnerController::class, 'create'])->name('visiting-learners.create');
Route::get('/visiting-learners-hub', function () {
    return view('education.visiting-learners-hub');
})->name('visiting-learners-hub');
Route::get('/visiting-learners-faqs', function () {
    return view('education.visiting-learners-faqs');
})->name('visiting-learners-faqs');

// Guidelines Route
Route::get('/guidelines', [FileController::class, 'index'])->name('guidelines');

// General Website Routes
Route::get('/about', function () {
    return view('about');
})->name('about');
Route::get('/services', function () {
    return view('services');
})->name('services');
Route::get('/careers', function () {
    return view('careers');
})->name('careers');
Route::get('/education', function () {
    return view('education');
})->name('education');
Route::get('/links', function () {
    return view('links');
})->name('links');
Route::get('/donate', function () {
    return view('donate');
})->name('donate');
Route::get('/short-courses', function () {
    return view('education.short-courses');
})->name('short-courses');
Route::get('/brochure-download', function () {
    return view('education.brochure-download');
})->name('brochure-download');
Route::get('/research', function () {
    return view('education.research');
})->name('research');

// Booking Routes
Route::get('/booking', [BookingController::class, 'show'])->name('booking.show');
Route::get('/booking/internal', [BookingController::class, 'internal'])->name('booking.internal');
Route::get('/dashboard/{status}', [BookingController::class, 'dashboard'])->middleware('web', 'auth.booking')->name('booking.dashboard.status');
Route::get('/booking/dashboard/{status?}', [BookingController::class, 'dashboard'])->middleware('web', 'auth.booking')->name(name: 'booking.dashboard');
Route::post('/booking/internal/submit', [BookingController::class, 'submitInternalAppointments'])->name('booking.submitInternal');
Route::post('/booking/submit', [BookingController::class, 'submitExternalAppointment'])->name('booking.submitExternal'); //external
Route::get('/booking/search', [BookingController::class, 'searchBookingPatients'])->name('booking.search');
Route::post('/booking/external/approve/{appointmentNumber}', [BookingController::class, 'approveExternalAppointment'])->middleware('web', 'auth.booking')->name('booking.approveExternal');
Route::post('/booking/{id}/cancel', [BookingController::class, 'cancelAppointment'])->middleware('web', 'auth.booking')->name('booking.cancel');
Route::post('/booking/{id}/reapprove', [BookingController::class, 'reapproveAppointment'])->middleware('web', 'auth.booking')->name('booking.reapprove');
Route::get('/booking/view/{id}/{status}', [BookingController::class, 'view'])->name('booking.view');
Route::get('/booking/edit/{id}/{status}', [BookingController::class, 'edit'])->middleware('web', 'auth.booking')->name('booking.edit');
Route::put('/booking/update/{id}', [BookingController::class, 'update'])->middleware('web', 'auth.booking')->name('booking.update');
Route::post('/booking/reschedule/{id}', [BookingController::class, 'reschedule'])->middleware('web', 'auth.booking')->name('booking.reschedule');
Route::post('/booking/appointments/mark-came', [BookingController::class, 'markAppointmentsCame'])->name('booking.mark-came');
Route::post('/booking/appointments/mark-missed', [BookingController::class, 'markAppointmentsMissed'])->name('booking.mark-missed');

Route::get('/appointments/cancelled', [BookingController::class, 'appointmentsCancelled'])->name('appointments.cancelled');
Route::delete('/booking/{id}/delete', [BookingController::class, 'deleteAppointment'])->name('booking.delete');
Route::get('/booking/view-limits', [BookingController::class, 'viewLimits'])->name('booking.viewLimits');
Route::patch('/booking/specializations/{specialization_id}/limit', [BookingController::class, 'updateLimit'])->name('booking.updateLimit');
Route::get('/booking/specialization-counts', [BookingController::class, 'getSpecializationCounts'])->name('booking.getSpecializationCounts');

Route::get('/booking/reports', [BookingController::class, 'reports'])->middleware('web', 'auth.booking')->name('booking.reports');
Route::get('/booking/report-details', [BookingController::class, 'detailedReport'])->name('booking.detailed-report');
Route::get('/booking/specialization-limits', [BookingController::class, 'specializationLimits'])->middleware('web', 'auth.booking')->name('booking.specialization.limits');
Route::post('/booking/specialization-limits/update', [BookingController::class, 'updateSpecializationLimit'])->middleware('web', 'auth.booking')->name('booking.specialization.update.limit');
//calender
Route::get('/booking/calendar', [BookingController::class, 'calendar'])->middleware('web', 'auth.booking')->name('booking.calendar');
Route::post('/booking/add-holiday', [BookingController::class, 'addHoliday'])->middleware('web', 'auth.booking')->name('booking.addHoliday');
// Route::get('/booking/reminders', [BookingController::class, 'reminders'])->name('booking.reminders');
// Route::post('booking/{id}/clear', [BookingController::class, 'clearAppointment'])->name('booking.clear');
// Route::post('booking/bulk-clear-reminders', [BookingController::class, 'bulkClearReminders'])->name('booking.bulkClearReminders');
Route::get('/booking/add', [BookingController::class, 'add'])->name('booking.add');
Route::post('/booking/appointments/save-tracing', [BookingController::class, 'saveBookingTracing'])->name('booking.save-tracing');
Route::get('/appointments/status-filter/{status}', [BookingController::class, 'dashboard'])->name('booking.status-filter');
Route::prefix('booking')->name('booking.')->middleware(['auth:booking'])->group(function () {

    // User Management Routes
    Route::get('users', [BookingAuthController::class, 'index'])->name('auth.users.index');
    Route::get('users/create', [BookingAuthController::class, 'create'])->name('auth.users.create');
    Route::post('users', [BookingAuthController::class, 'store'])->name('auth.users.store');
    Route::get('users/{user}/edit', [BookingAuthController::class, 'edit'])->name('auth.users.edit');
    Route::put('users/{user}', [BookingAuthController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [BookingAuthController::class, 'destroy'])->name('users.destroy');

    // Additional User Actions
    Route::get('users/{user}/change-password', [BookingAuthController::class, 'showChangePasswordForm'])
        ->name('auth.users.change-password');
    Route::patch('users/{user}/change-password', [BookingAuthController::class, 'changePassword'])
        ->name('users.change-password');
    Route::patch('users/{user}/toggle-status', [BookingAuthController::class, 'toggleStatus'])
        ->name('users.toggle-status');
});
// Doctor Management (admin/superadmin only)
Route::prefix('auth/doctors')->name('booking.auth.doctors.')->group(function () {
    Route::get('/', [BookingAuthController::class, 'doctorsIndex'])->name('index');
    Route::get('/create', [BookingAuthController::class, 'doctorCreate'])->name('create');
    Route::post('/', [BookingAuthController::class, 'doctorStore'])->name('store');
    Route::get('/{id}/edit', [BookingAuthController::class, 'doctorEdit'])->name('edit');
    Route::put('/{id}', [BookingAuthController::class, 'doctorUpdate'])->name('update');
});
Route::get('/booking/login', [BookingAuthController::class, 'showBookingLoginForm'])->name('booking.login');
Route::post('/booking/login', [BookingAuthController::class, 'bookingLogin']);
Route::post('/booking/logout', [BookingAuthController::class, 'bookingLogout'])->name('booking.logout');
// Route::post('/booking/logout', [BookingAuthController::class, 'logout'])->name('booking.logout');
Route::get('/booking/password/reset', [BookingAuthController::class, 'showBookingForgotPasswordForm'])->name('booking.password.request');
Route::post('/booking/password/email', [BookingAuthController::class, 'sendBookingResetLinkEmail'])->name('booking.password.email');
Route::get('/booking/password/reset/{token}', [BookingAuthController::class, 'showBookingResetPasswordForm'])->name(name: 'booking.password.reset');
Route::post('/booking/password/reset', [BookingAuthController::class, 'resetBookingPassword'])->name('booking.password.update');

//booking alerts
Route::get('/booking/alerts', [AlertController::class, 'getActiveAlerts'])->name('booking.alerts');
Route::get('/booking/resolved-alerts', [AlertController::class, 'getResolvedAlerts'])->name('booking.resolved_alerts');
Route::get('/alerts/patients', [AlertController::class, 'getPatients'])->name('alerts.patients');
Route::post('/alerts', [AlertController::class, 'store'])->name('alerts.store');
Route::post('/alerts/store-new-patient', [AlertController::class, 'storeNewPatient'])->name('alerts.storeNewPatient');
Route::patch('/alerts/{id}/resolve', [AlertController::class, 'resolve'])->name('alerts.resolve');
Route::patch('/alerts/{id}/reopen', [AlertController::class, 'reopen'])->name('alerts.reopen');
Route::patch('/alerts/bulk-resolve', [AlertController::class, 'bulkResolve'])->name('alerts.bulkResolve');
Route::patch('/alerts/bulk-reopen', [AlertController::class, 'bulkReopen'])->name('alerts.bulkReopen');
Route::get('/alerts/{id}/details', [AlertController::class, 'getAlertDetails'])->name('alerts.details');
Route::get('/alerts/patients', [AlertController::class, 'getPatients'])->name('alerts.patients');

Route::get('/booking/branch/{branch}', [App\Http\Controllers\BookingController::class, 'booked_branch'])->name('booking.branch');
Route::get('/booking/doctor/diary', [BookingDoctorController::class, 'bookingDiary'])->middleware('web', 'auth.booking')->name('booking.doctor.diary');
//SMS Integration Routes

Route::match(['get', 'post'], '/booking/sms/reminders', [SmsIntergrationController::class, 'showBulk'])->name('booking.reminders');
Route::get('/booking/sms/delivery-log', [SmsIntergrationController::class, 'showDeliveryLog'])->name('booking.delivery_log');
Route::get('/booking/sms/search', [SmsIntergrationController::class, 'searchPatients'])->name('booking.searchPatients');
Route::post('/booking/sms/send-bulk-sms', [SmsIntergrationController::class, 'sendBulkSMS'])->name('booking.sendBulkSMS');
Route::get('/booking/sms/delivery-log-data', [SmsIntergrationController::class, 'getDeliveryLog'])->name('booking.getDeliveryLog');
Route::get('/booking/sms/message-templates', [SmsIntergrationController::class, 'getTemplates'])->name('booking.getTemplates');
Route::post('/booking/sms/save-template', [SmsIntergrationController::class, 'saveTemplate'])->name('booking.saveTemplate');

// Specific Services Routes
Route::get('/outpatient', function () {
    return view('services.outpatient');
})->name('outpatient');
Route::get('/family-clinic', function () {
    return view('services.family-clinic');
})->name('family-clinic');
Route::get('/dental-clinic', function () {
    return view('services.dental-clinic');
})->name('dental-clinic');
Route::get('/palliative-clinic', function () {
    return view('services.palliative-clinic');
})->name('palliative-clinic');
Route::get('/oncology', function () {
    return view('services.oncology');
})->name('oncology');
Route::get('/physiotherapy', function () {
    return view('services.physiotherapy');
})->name('physiotherapy');
Route::get('/chronic-care-clinic', function () {
    return view('services.chronic-care-clinic');
})->name('chronic-care-clinic');
Route::get('/mental-health-clinic', function () {
    return view('services.mental-health-clinic');
})->name('mental-health-clinic');
Route::get('/icu', function () {
    return view('services.icu');
})->name('icu');
Route::get('/hdu', function () {
    return view('services.hdu');
})->name('hdu');
Route::get('/medical-ward', function () {
    return view('services.medical-ward');
})->name('medical-ward');
Route::get('/surgical-care', function () {
    return view('services.surgical-care');
})->name('surgical-care');
Route::get('/nutrition-care', function () {
    return view('services.nutrition-care');
})->name('nutrition-care');
Route::get('/paediatrics', function () {
    return view('services.paediatrics');
})->name('paediatrics');
Route::get('/picu', function () {
    return view('services.picu');
})->name('picu');
Route::get('/nicu', function () {
    return view('services.nicu');
})->name('nicu');
Route::get('/daycase-surgeries', function () {
    return view('services.daycase-surgeries');
})->name('daycase-surgeries');
Route::get('/elective-surgeries', function () {
    return view('services.elective-surgeries');
})->name('elective-surgeries');
Route::get('/kids-surgeries', function () {
    return view('services.kids-surgeries');
})->name('kids-surgeries');
Route::get('/pharmacy', function () {
    return view('services.pharmacy');
})->name('pharmacy');
Route::get('/pathology', function () {
    return view('services.pathology');
})->name('pathology');
Route::get('/laboratory', function () {
    return view('services.laboratory');
})->name('laboratory');
Route::get('/emergency-surgeries', function () {
    return view('services.emergency-surgeries');
})->name('emergency-surgeries');
Route::get('/radiology', function () {
    return view('services.radiology');
})->name('radiology');
Route::get('/telemedicine-patient', function () {
    return view('services.telemedicine-patient');
})->name('telemedicine-patient');
Route::get('/telemedicine-doctor', function () {
    return view('services.telemedicine-doctor');
})->name('telemedicine-doctor');

// News and Info Routes
Route::get('/news', function () {
    return view('news');
})->name('news');
Route::get('/blog', function () {
    return view('blog');
})->name('blog');
Route::get('/newsletters', function () {
    return view('newsletters');
})->name('newsletters');
Route::get('/notices', function () {
    return view('notices');
})->name('notices');

// Organizational Routes
Route::get('/merchandise', function () {
    return view('merchandise');
})->name('merchandise');
Route::get('/history', function () {
    return view('history');
})->name('history');
Route::get('/historytext', function () {
    return view('historytext');
})->name('historytext');
Route::get('/history/{year}', function ($year) {
    return view('historydetail');
})->name('historydetail');
Route::get('/mission', function () {
    return view('mission');
})->name('mission');
Route::get('/strategy', function () {
    return view('strategy');
})->name('strategy');
Route::get('/audit-report', function () {
    return view('audit-report');
})->name('audit-report');
Route::get('/main-branch', function () {
    return view('main-branch');
})->name('main-branch');
Route::get('/westlands-branch', function () {
    return view('westlands-branch');
})->name('westlands-branch');
Route::get('/marira-branch', function () {
    return view('marira-branch');
})->name('marira-branch');
Route::get('/naivasha-branch', function () {
    return view('naivasha-branch');
})->name('naivasha-branch');

// Contact and Inquiry Routes
Route::get('/contact', function () {
    return view('contact');
})->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
Route::get('/inquiries', function () {
    return view('inquiries');
})->name('inquiries');
Route::get('/faq', function () {
    return view('faq');
})->name('faq');

// Gallery Route
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');

// Career Routes
Route::get('/careers-how-to-apply', function () {
    return view('careers-how-to-apply');
})->name('careers-how-to-apply');

// Email Management Routes
Route::get('/upload', [EmailController::class, 'showUploadForm'])->name('emails.upload_form');
Route::post('/upload', [EmailController::class, 'uploadEmails'])->name('emails.upload');
Route::get('/send-email', [EmailController::class, 'showSendEmailForm'])->name('emails.send_form');
Route::post('/send-email', [EmailController::class, 'sendBulkEmail'])->name('emails.send_bulk');
Route::get('/email-preview', [EmailController::class, 'showEmailPreview'])->name('emails.preview');

// Booking Routes
Route::get('/patient-booking', function () {
    return view('patient-booking');
})->name('patient-booking');
Route::get('/doctor-booking', function () {
    return view('doctor-booking');
})->name('doctor-booking');

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [AccountController::class, 'index'])->name('account.index');
    Route::get('/profile/edit', [AccountController::class, 'edit'])->name('account.edit');
    Route::patch('/profile', [AccountController::class, 'update'])->name('account.update');
    Route::delete('/profile', [AccountController::class, 'destroy'])->name('account.destroy');
});

// Patient Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/patient/profile', [PatientController::class, 'index'])->name('patient.index');
    Route::get('/patient/edit', [PatientController::class, 'edit'])->name('patient.edit');
    Route::get('/patient/update', [PatientController::class, 'update'])->name('patient.update');
    Route::get('/patient', [PatientController::class, 'destroy'])->name('patient.destroy');
});

// Admin Settings Routes
Route::middleware(['auth', 'role:administrator'])->group(function () {
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::get('/settings/assign-role', [SettingsController::class, 'assignRole'])->name('settings.assign-role');
    Route::get('/settings/roles-permissions', [SettingsController::class, 'rolesPermissions'])->name('settings.roles_permissions');
    Route::post('/settings/assign-role', [SettingsController::class, 'assignRole'])->name('settings.assign_role');
    Route::put('/settings/update-permissions/{role}', [SettingsController::class, 'updatePermissions'])->name('settings.update_permissions');
});

// Authenticated Email Routes
Route::middleware('auth')->group(function () {
    Route::get('/email-preview', [EmailController::class, 'showEmailPreview'])->name('emails.preview');
    Route::get('/emails/{id}', [HomeController::class, 'show'])->name('emails.show');
});

// Email Verification Routes
Route::middleware(['auth'])->group(function () {
    Route::get('verify-email', [EmailVerificationPromptController::class, '__invoke'])->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1')->name('verification.send');
});

// Password Reset Routes
Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');

// Registration Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Doctor Concern Route
Route::post('/submit-concern', [DoctorController::class, 'submitConcern'])->name('submit.concern');

// Service and Upload Routes
Route::get('/services/report', [ServiceController::class, 'showReports'])->name('services.report');
Route::get('/reports', [ServiceController::class, 'showReports'])->name('reports.show');
Route::get('/teleuploads', [ServiceController::class, 'showUploads'])->name('uploads.show');
Route::get('reports/{filename}', [ServiceController::class, 'downloadReport'])->name('reports.download');
Route::get('uploads/{filename}', [ServiceController::class, 'downloadReport'])->name('uploads.download');
Route::post('/uploads/store', [UploadController::class, 'store'])->name('uploads.store');
Route::get('/services/uploads', [ServiceController::class, 'showUploads'])->name('services.uploads');
Route::get('/services/downloads', [ServiceController::class, 'showDownloads'])->name('services.downloads');
Route::get('/services/book/{serviceId}', [ServiceController::class, 'showBookingForm'])->name('services.showBookingForm');
Route::post('/services/confirm_payment/{serviceId}', [ServiceController::class, 'confirmPayment'])->name('services.confirm_payment');
Route::post('/services/book/{serviceId}', [ServiceController::class, 'book'])->name('services.book');

// Include Authentication Routes
require __DIR__ . '/auth.php';
