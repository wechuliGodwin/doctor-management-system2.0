<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\BookingUserAuth;
use App\Models\BkDoctor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;

class BookingAuthController extends Controller
{
    public function __construct()
    {
        // Apply auth middleware only to methods that require authentication
        $this->middleware('auth:booking')->except([
            'showBookingLoginForm',
            'bookingLogin',
            'showBookingForgotPasswordForm',
            'sendBookingResetLinkEmail',
            'showBookingResetPasswordForm',
            'resetBookingPassword'
        ]);

        // Apply role-based middleware only to user management methods
        $this->middleware(function ($request, $next) {
            $user = Auth::guard('booking')->user();
            if (!$user || !in_array($user->role, ['admin', 'superadmin'])) {
                abort(403, 'Unauthorized access.');
            }
            return $next($request);
        })->only([
            'index',
            'create',
            'store',
            'edit',
            'update',
            'showChangePasswordForm',
            'changePassword',
            'toggleStatus',
            'destroy'
        ]);
    }

    protected $casts = [
        'switchable_branches' => 'array', // Automatically cast JSON to array
        'branch_permissions' => 'array',
        'is_active' => 'boolean',
    ];

    // Optional: Explicit accessor for additional control
    public function getSwitchableBranchesAttribute($value)
    {
        if (is_string($value)) {
            return json_decode($value, true) ?? [];
        }
        return $value ?? [];
    }

    // Optional: Explicit accessor for branch_permissions
    public function getBranchPermissionsAttribute($value)
    {
        if (is_string($value)) {
            return json_decode($value, true) ?? [];
        }
        return $value ?? [];
    }
    //Display a listing of users based on role permissions
    public function index(Request $request)
    {
        $currentUser = Auth::guard('booking')->user();
        $query = BookingUserAuth::query();

        // Role-based filtering
        $selectedBranch = session('selected_branch', $currentUser->hospital_branch);

        // Apply branch filtering for both admin and superadmin
        $query->where('hospital_branch', $selectedBranch);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Role filter
        if ($request->filled('role_filter')) {
            $query->where('role', $request->get('role_filter'));
        }

        // Hospital branch filter (only for superadmin)
        if ($request->filled('branch_filter') && $currentUser->role === 'superadmin') {
            $query->where('hospital_branch', $request->get('branch_filter'));
        }

        // Status filter
        if ($request->filled('status_filter')) {
            $status = $request->get('status_filter');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        $hospital_branches = $this->getHospitalBranchEnumValues();
        $roles = $this->getUserRolesEnumValues();

        return view('booking.auth.users.index', compact('users', 'hospital_branches', 'roles', 'currentUser', 'selectedBranch'));
    }

    // Show the form for creating a new user
    public function create()
    {
        $hospital_branches = $this->getHospitalBranchEnumValues();
        $roles = $this->getUserRolesEnumValues();

        return view('booking.auth.users.create', compact('hospital_branches', 'roles'));
    }

    //Store a newly created user

    // public function store(Request $request)
    // {
    //     $currentUser = Auth::guard('booking')->user();

    //     $validated = $request->validate([
    //         'full_name' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'string', 'email', 'max:255', 'unique:booking_user_auth'],
    //         'password' => ['required', 'string', 'min:6', 'confirmed'],
    //         'role' => ['required', 'in:' . implode(',', $this->getUserRolesEnumValues())],
    //         'hospital_branch' => ['required', 'in:' . implode(',', $this->getHospitalBranchEnumValues())],
    //     ]);

    //     // Admins can only create users for their own hospital branch
    //     if ($currentUser->role === 'admin') {
    //         $validated['hospital_branch'] = $currentUser->hospital_branch;
    //     }

    //     // Set switchable_branches to include only the hospital_branch by default
    //     $user = BookingUserAuth::create([
    //         'full_name' => $validated['full_name'],
    //         'email' => $validated['email'],
    //         'password' => Hash::make($validated['password']),
    //         'role' => $validated['role'],
    //         'hospital_branch' => $validated['hospital_branch'],
    //         'switchable_branches' => [$validated['hospital_branch']], // Default to hospital_branch
    //         'is_active' => true,
    //     ]);

    //     Log::info('User created', [
    //         'created_user_id' => $user->id,
    //         'created_by' => $currentUser->id,
    //         'email' => $user->email,
    //         'switchable_branches' => $user->switchable_branches,
    //     ]);

    //     return redirect()->route('booking.auth.users.index')->with('success', 'User created successfully.');
    // }

    public function store(Request $request)
    {
        $currentUser = Auth::guard('booking')->user();

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:booking_user_auth'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role' => ['required', 'in:' . implode(',', $this->getUserRolesEnumValues())],
            'hospital_branch' => ['required', 'in:' . implode(',', $this->getHospitalBranchEnumValues())],
        ]);

        // Admins can only create users for their own hospital branch
        if ($currentUser->role === 'admin') {
            $validated['hospital_branch'] = $currentUser->hospital_branch;
        }

        // Set switchable_branches to include only the hospital_branch by default
        $user = BookingUserAuth::create([
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'hospital_branch' => $validated['hospital_branch'],
            'switchable_branches' => [$validated['hospital_branch']], // Default to hospital_branch
            // 'branch_permissions' => [$validated['hospital_branch'] => 'read-write'], // Default permissions
            'is_active' => true,
        ]);

        Log::info('User created', [
            'created_user_id' => $user->id,
            'created_by' => $currentUser->id,
            'email' => $user->email,
            'switchable_branches' => $user->switchable_branches,
            'branch_permissions' => $user->branch_permissions,
        ]);

        return redirect()->route('booking.auth.users.index')->with('success', 'User created successfully.');
    }
    //Show the form for editing a user
    // public function edit($id)
    // {
    //     $currentUser = Auth::guard('booking')->user();
    //     $user = BookingUserAuth::findOrFail($id);

    //     // Check if admin can edit this user (same hospital branch)
    //     if ($currentUser->role === 'admin' && $user->hospital_branch !== $currentUser->hospital_branch) {
    //         abort(403, 'You can only edit users from your hospital branch.');
    //     }

    //     $hospital_branches = $this->getHospitalBranchEnumValues();
    //     $roles = $this->getUserRolesEnumValues();

    //     return view('booking.auth.users.edit', compact('user', 'hospital_branches', 'roles', 'currentUser'));
    // }
    public function edit($id)
    {
        $currentUser = Auth::guard('booking')->user();
        $user = BookingUserAuth::findOrFail($id);

        // Check if admin can edit this user (same hospital branch)
        if ($currentUser->role === 'admin' && $user->hospital_branch !== $currentUser->hospital_branch) {
            abort(403, 'You can only edit users from your hospital branch.');
        }

        $hospital_branches = $this->getHospitalBranchEnumValues();
        $roles = $this->getUserRolesEnumValues();

        return view('booking.auth.users.edit', compact('user', 'hospital_branches', 'roles', 'currentUser'));
    }
    //Update user details
    // public function update(Request $request, BookingUserAuth $user)
    // {
    //     $currentUser = Auth::guard('booking')->user();

    //     // Define validation rules
    //     $rules = [
    //         'full_name' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'email', 'max:255', Rule::unique('booking_user_auth')->ignore($user->id)],
    //         'role' => ['required', 'in:user,admin,superadmin'],
    //         'hospital_branch' => ['required', 'in:' . implode(',', $this->getHospitalBranchEnumValues())],
    //         'is_active' => ['boolean'],
    //     ];

    //     // Only superadmins can update switchable_branches and branch_permissions
    //     if ($currentUser->role === 'superadmin') {
    //         $rules['switchable_branches'] = ['nullable', 'array'];
    //         $rules['switchable_branches.*'] = ['in:' . implode(',', $this->getHospitalBranchEnumValues())];
    //         $rules['branch_permissions'] = ['nullable', 'array'];
    //         $rules['branch_permissions.*'] = ['in:read-only,read-write'];
    //     }

    //     $validated = $request->validate($rules);

    //     // Ensure hospital_branch is included in switchable_branches
    //     if ($currentUser->role === 'superadmin') {
    //         $switchableBranches = $validated['switchable_branches'] ?? [];
    //         if (!in_array($validated['hospital_branch'], $switchableBranches)) {
    //             $switchableBranches[] = $validated['hospital_branch'];
    //         }
    //         $validated['switchable_branches'] = array_unique($switchableBranches);

    //         // Build branch_permissions array
    //         $branchPermissions = [];
    //         foreach ($validated['switchable_branches'] as $branch) {
    //             $branchPermissions[$branch] = $validated['branch_permissions'][$branch] ?? 'read-only';
    //         }
    //         $validated['branch_permissions'] = $branchPermissions;
    //     } else {
    //         // Non-superadmins get only their hospital_branch with read-write
    //         $validated['switchable_branches'] = [$validated['hospital_branch']];
    //         $validated['branch_permissions'] = [$validated['hospital_branch'] => 'read-write'];
    //     }

    //     // Convert arrays to JSON for storage
    //     $validated['switchable_branches'] = json_encode($validated['switchable_branches']);
    //     $validated['branch_permissions'] = json_encode($validated['branch_permissions']);

    //     // Update the user
    //     $user->update($validated);

    //     Log::info('User updated', [
    //         'user_id' => $user->id,
    //         'updated_by' => $currentUser->id,
    //         'switchable_branches' => $validated['switchable_branches'],
    //         'branch_permissions' => $validated['branch_permissions'],
    //     ]);

    //     return redirect()->route('booking.auth.users.index')->with('success', 'User updated successfully.');
    // }
    public function update(Request $request, BookingUserAuth $user)
    {
        $currentUser = Auth::guard('booking')->user();

        // Define validation rules
        $rules = [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('booking_user_auth')->ignore($user->id)],
            'role' => ['required', 'in:user,admin,superadmin'],
            'hospital_branch' => ['required', 'in:' . implode(',', $this->getHospitalBranchEnumValues())],
            'is_active' => ['boolean'],
        ];

        // Only superadmins can update switchable_branches and branch_permissions
        if ($currentUser->role === 'superadmin') {
            $rules['switchable_branches'] = ['nullable', 'array'];
            $rules['switchable_branches.*'] = ['in:' . implode(',', $this->getHospitalBranchEnumValues())];
            $rules['branch_permissions'] = ['nullable', 'array'];
            // $rules['branch_permissions.*'] = ['in:read-only,read-write'];
        }

        $validated = $request->validate($rules);

        // Ensure hospital_branch is included in switchable_branches
        if ($currentUser->role === 'superadmin') {
            $switchableBranches = $validated['switchable_branches'] ?? [];
            if (!in_array($validated['hospital_branch'], $switchableBranches)) {
                $switchableBranches[] = $validated['hospital_branch'];
            }
            $validated['switchable_branches'] = array_unique($switchableBranches);

            // Build branch_permissions array
            $branchPermissions = [];
            foreach ($validated['switchable_branches'] as $branch) {
                $branchPermissions[$branch] = $validated['branch_permissions'][$branch] ?? 'read-only';
            }
            // Ensure primary hospital_branch has read-write permission
            // $branchPermissions[$validated['hospital_branch']] = 'read-write';
            $validated['branch_permissions'] = $branchPermissions;
        } else {
            // Non-superadmins get only their hospital_branch with read-write
            $validated['switchable_branches'] = [$validated['hospital_branch']];
            // $validated['branch_permissions'] = [$validated['hospital_branch'] => 'read-write'];
        }

        // Update the user (no manual JSON encoding needed due to array cast)
        $user->update($validated);

        Log::info('User updated', [
            'user_id' => $user->id,
            'updated_by' => $currentUser->id,
            'switchable_branches' => $validated['switchable_branches'],
            // 'branch_permissions' => $validated['branch_permissions'],
        ]);

        return redirect()->route('booking.auth.users.index')->with('success', 'User updated successfully.');
    }
    public function switchBranch(Request $request)
    {
        $user = Auth::guard('booking')->user();
        $validated = $request->validate([
            'branch' => ['required', 'in:' . implode(',', $this->getHospitalBranchEnumValues())],
        ]);

        // Check if the user is allowed to switch to the selected branch
        $switchableBranches = $user->switchable_branches ?? [$user->hospital_branch];
        if (!in_array($validated['branch'], $switchableBranches)) {
            Log::warning('Unauthorized branch switch attempt', [
                'user_id' => $user->id,
                'attempted_branch' => $validated['branch'],
                'switchable_branches' => $switchableBranches,
            ]);
            return back()->withErrors(['branch' => 'You are not authorized to switch to this branch.']);
        }

        // Update the user's hospital_branch
        $user->update(['hospital_branch' => $validated['branch']]);

        // Store the selected branch in session for data filtering
        session(['selected_branch' => $validated['branch']]);

        Log::info('User switched branch', [
            'user_id' => $user->id,
            'new_branch' => $validated['branch'],
            'switchable_branches' => $switchableBranches,
        ]);

        return redirect()->back()->with('success', 'Branch switched successfully.');
    }
    public function showChangePasswordForm($id)
    {
        $currentUser = Auth::guard('booking')->user();
        $user = BookingUserAuth::findOrFail($id);

        // Check permissions
        if ($currentUser->role === 'admin' && $user->hospital_branch !== $currentUser->hospital_branch) {
            abort(403, 'You can only change passwords for users from your hospital branch.');
        }

        return view('booking.auth.users.change-password', compact('user', 'currentUser'));
    }

    //Change user password
    public function changePassword(Request $request, $id)
    {
        $currentUser = Auth::guard('booking')->user();
        $user = BookingUserAuth::findOrFail($id);

        // Check permissions
        if ($currentUser->role === 'admin' && $user->hospital_branch !== $currentUser->hospital_branch) {
            abort(403, 'You can only change passwords for users from your hospital branch.');
        }

        $request->validate([
            'new_password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
            ],
        ]);

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        Log::info('Password changed', [
            'user_id' => $user->id,
            'changed_by' => $currentUser->id,
        ]);

        return back()->with('success', 'Password changed successfully.');
    }
    //Toggle user active status
    public function toggleStatus($id)
    {
        $currentUser = Auth::guard('booking')->user();
        $user = BookingUserAuth::findOrFail($id);

        // Check permissions
        if ($currentUser->role === 'admin' && $user->hospital_branch !== $currentUser->hospital_branch) {
            abort(403, 'You can only manage users from your hospital branch.');
        }

        // Prevent users from deactivating themselves
        if ($user->id === $currentUser->id) {
            return back()->with('error', 'You cannot change your own status.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';

        Log::info("User {$status}", [
            'user_id' => $user->id,
            'changed_by' => $currentUser->id,
        ]);

        return back()->with('success', "User {$status} successfully.");
    }
    //Delete user (soft delete or permanent based on requirements)
    public function destroy($id)
    {
        $currentUser = Auth::guard('booking')->user();
        $user = BookingUserAuth::findOrFail($id);

        // Only superadmin can delete users
        if ($currentUser->role !== 'superadmin') {
            abort(403, 'Only superadmin can delete users.');
        }

        // Prevent users from deleting themselves
        if ($user->id === $currentUser->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        Log::info('User deleted', [
            'deleted_user_id' => $user->id,
            'deleted_by' => $currentUser->id,
        ]);

        return back()->with('success', 'User deleted successfully.');
    }
    //Show booking login form
    public function showBookingLoginForm()
    {
        Log::info('Accessing booking login form', ['intended' => session('url.intended', 'none')]);
        return view('booking.auth.login');
    }
    //Handle booking login
    public function bookingLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Check if user exists and is active
        $user = BookingUserAuth::where('email', $credentials['email'])->first();
        if (!$user) {
            Log::warning('Login failed: User not found', ['email' => $credentials['email']]);
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        if (!$user->is_active) {
            Log::warning('Login failed: User is inactive', ['email' => $credentials['email']]);
            return back()->withErrors([
                'email' => 'Your account is inactive. Please contact an administrator.',
            ])->onlyInput('email');
        }

        if (Auth::guard('booking')->attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::guard('booking')->user();

            Log::info('Login successful for booking', [
                'email' => $credentials['email'],
                'user_id' => $user->id,
                'hospital_branch' => $user->hospital_branch ?? 'none',
                'session_id' => session()->getId()
            ]);

            $intended = session('url.intended', route('booking.dashboard'));
            session()->forget('url.intended');
            return redirect()->intended($intended)->with('success', 'Logged in successfully');
        }

        Log::warning('Login failed for booking', [
            'email' => $credentials['email'],
            'user_exists' => $user ? 'yes' : 'no',
        ]);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
    //Handle booking logout
    public function bookingLogout(Request $request)
    {
        Log::info('Logout attempt for booking', [
            'user_id' => Auth::guard('booking')->id(),
            'session_id' => session()->getId()
        ]);

        Auth::guard('booking')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info('Logout successful for booking');
        return redirect()->route('booking.login')->with('success', 'Logged out successfully');
    }
    //Show forgot password form
    public function showBookingForgotPasswordForm()
    {
        return view('booking.auth.forgot-password');
    }
    //Send password reset link
    public function sendBookingResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::broker('booking_users')->sendResetLink(
            $request->only('email')
        );

        Log::info('Password reset link status for booking', ['status' => $status]);

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }
    //Show password reset form
    public function showBookingResetPasswordForm($token)
    {
        Log::info('Showing reset password form for booking', ['token' => $token]);
        return view('booking.auth.reset-password', ['token' => $token]);
    }
    //Reset password
    public function resetBookingPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'min:6',
                'confirmed',
                'regex:/^(?=.*[A-Z])(?=.*\W).+$/'
            ],
        ], [
            'password.regex' => 'The password must contain at least one uppercase letter and one symbol.',
        ]);

        Log::info('Password reset attempt for booking', ['email' => $request->email]);

        $status = Password::broker('booking_users')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
                $user->save();
                Log::info('Password reset successful for booking', ['email' => $user->email]);
            }
        );

        Log::info('Password reset status for booking', ['status' => $status]);

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('booking.login')->with('status', 'Password has been reset successfully.')
            : back()->withErrors(['email' => __($status)]);
    }
    //Display a listing of doctors
    public function doctorsIndex(Request $request)
    {
        $currentUser = Auth::guard('booking')->user();
        $query = BkDoctor::query();

        // Role-based filtering
        $selectedBranch = session('selected_branch', $currentUser->hospital_branch);

        // Apply branch filtering for both admin and superadmin
        $query->where('hospital_branch', $selectedBranch);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('doctor_name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Hospital branch filter (only for superadmin)
        if ($request->filled('branch_filter') && $currentUser->role === 'superadmin') {
            $query->where('hospital_branch', $request->get('branch_filter'));
        }

        // Department filter
        if ($request->filled('department_filter')) {
            $query->where('department', $request->get('department_filter'));
        }

        $doctors = $query->orderBy('doctor_name')->paginate(15);
        $hospital_branches = $this->getHospitalBranchEnumValues();
        $departments = $this->getDepartmentEnumValues();

        return view('booking.auth.doctors.index', compact('doctors', 'hospital_branches', 'departments', 'currentUser', 'selectedBranch'));
    }

    /**
     * Show the form for creating a new doctor
     */
    public function doctorCreate()
    {
        $hospital_branches = $this->getHospitalBranchEnumValues();
        $departments = $this->getDepartmentEnumValues();
        // $roles = $this->getUserRolesEnumValues();

        return view('booking.auth.doctors.create', compact('hospital_branches', 'departments'));
    }

    /**
     * Store a newly created doctor
     */
    public function doctorStore(Request $request)
    {
        $currentUser = Auth::guard('booking')->user();

        $validated = $request->validate([
            'doctor_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:bk_doctor,email', 'unique:booking_user_auth,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'hospital_branch' => ['required', 'in:' . implode(',', $this->getHospitalBranchEnumValues())],
            'department' => ['required', 'in:' . implode(',', $this->getDepartmentEnumValues())],
            // 'password' => ['required', 'string', 'min:6', 'confirmed'],
            // 'role' => ['required', 'in:' . implode(',', $this->getUserRolesEnumValues())],
        ]);

        // Admins can only create doctors for their own hospital branch
        if ($currentUser->role === 'admin') {
            $validated['hospital_branch'] = $currentUser->hospital_branch;
        }

        // Create doctor
        $doctor = BkDoctor::create([
            'doctor_name' => $validated['doctor_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'hospital_branch' => $validated['hospital_branch'],
            'department' => $validated['department'],
        ]);

        // Create associated user account
        // $user = BookingUserAuth::create([
        //     'full_name' => $validated['doctor_name'],
        //     'email' => $validated['email'],
        //     'password' => Hash::make($validated['password']),
        //     'role' => $validated['role'],
        //     'hospital_branch' => $validated['hospital_branch'],
        //     'is_active' => true,
        // ]);

        Log::info('Doctor and user account created', [
            'doctor_id' => $doctor->id,
            // 'user_id' => $user->id,
            'created_by' => $currentUser->id,
            'email' => $doctor->email,
        ]);

        return redirect()->route('booking.auth.doctors.index')->with('success', 'Doctor created successfully.');
    }

    /**
     * Show the form for editing a doctor
     */
    public function doctorEdit($id)
    {
        $currentUser = Auth::guard('booking')->user();
        $doctor = BkDoctor::findOrFail($id);

        if ($currentUser->role === 'admin' && $doctor->hospital_branch !== $currentUser->hospital_branch) {
            abort(403, 'You can only edit doctors from your hospital branch.');
        }

        $hospital_branches = $this->getHospitalBranchEnumValues();
        $departments = $this->getDepartmentEnumValues();

        return view('booking.auth.doctors.edit', compact('doctor', 'hospital_branches', 'departments', 'currentUser'));
    }

    /**
     * Update doctor details
     */
    public function doctorUpdate(Request $request, $id)
    {
        $currentUser = Auth::guard('booking')->user();
        $doctor = BkDoctor::findOrFail($id);

        if ($currentUser->role === 'admin' && $doctor->hospital_branch !== $currentUser->hospital_branch) {
            abort(403, 'You can only edit doctors from your hospital branch.');
        }

        $validated = $request->validate([
            'doctor_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', Rule::unique('bk_doctor')->ignore($doctor->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'hospital_branch' => ['required', 'in:' . implode(',', $this->getHospitalBranchEnumValues())],
            'department' => ['required', 'in:' . implode(',', $this->getDepartmentEnumValues())],
        ]);

        if ($currentUser->role === 'admin') {
            unset($validated['hospital_branch']);
        }

        $doctor->update($validated);

        // Update associated user account if exists
        // $user = BookingUserAuth::where('email', $doctor->email)->first();
        // if ($user) {
        //     $user->update([
        //         'full_name' => $validated['doctor_name'],
        //         'email' => $validated['email'],
        //         'hospital_branch' => $validated['hospital_branch'] ?? $user->hospital_branch,
        //     ]);
        // }

        Log::info('Doctor updated', [
            'doctor_id' => $doctor->id,
            'updated_by' => $currentUser->id,
        ]);

        return redirect()->route('booking.auth.doctors.index')->with('success', 'Doctor updated successfully.');
    }

    /**
     * Get department enum values
     */
    protected function getDepartmentEnumValues()
    {
        return DB::table('bk_doctor')->distinct()->pluck('department')->filter()->values()->toArray();
    }

    /**
     * Get hospital branch enum values
     */
    protected function getHospitalBranchEnumValues()
    {
        return ['kijabe', 'westlands', 'naivasha', 'marira'];
    }

    /**
     * Get user roles enum values
     */
    protected function getUserRolesEnumValues()
    {
        return ['user', 'admin', 'superadmin'];
    }
}
