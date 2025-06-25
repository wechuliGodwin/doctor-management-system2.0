@extends('layouts.app')

@section('content')
    <div class="container mx-auto my-12 p-6">
        <h1 class="text-4xl font-bold text-center text-[#159ed5] mb-8">Visiting Learners Application Form</h1>

        <!-- Application Form -->
        <form action="{{ route('visiting-learners.store') }}" method="POST" enctype="multipart/form-data"
            class="bg-white shadow-md rounded-lg p-6">
            @csrf

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('success'))
                <div class="bg-[#159ed5] border border-[#107ba3] text-white px-4 py-3 rounded-lg mb-6">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Personal Information</h2>
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-bold mb-2">Name:</label>
                <input type="text" id="name" name="name" class="w-full p-2 border rounded-lg" required>
            </div>
            <div class="mb-4">
                <label for="phone_number" class="block text-gray-700 font-bold mb-2">Phone Number:</label>
                <input type="text" id="phone_number" name="phone_number" class="w-full p-2 border rounded-lg">
            </div>
            <div class="mb-4">
                <label for="contact_email" class="block text-gray-700 font-bold mb-2">Contact Email:</label>
                <input type="email" id="contact_email" name="contact_email" class="w-full p-2 border rounded-lg" required>
            </div>
            <div class="mb-4">
                <label for="current_institution" class="block text-gray-700 font-bold mb-2">Current Institution:</label>
                <input type="text" id="current_institution" name="current_institution" class="w-full p-2 border rounded-lg">
            </div>
            <div class="mb-4">
                <label for="location" class="block text-gray-700 font-bold mb-2">Location:</label>
                <input type="text" id="location" name="location" class="w-full p-2 border rounded-lg">
            </div>

            <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Rotation Information</h2>
            <div class="mb-4">
                <label for="specialty" class="block text-gray-700 font-bold mb-2">Specialty:</label>
                <input type="text" id="specialty" name="specialty" class="w-full p-2 border rounded-lg">
            </div>
            <div class="mb-4">
                <label for="year_of_training" class="block text-gray-700 font-bold mb-2">Year of Training:</label>
                <input type="text" id="year_of_training" name="year_of_training" class="w-full p-2 border rounded-lg">
            </div>
            <div class="mb-4">
                <label for="preferred_start_date" class="block text-gray-700 font-bold mb-2">Preferred Start Date:</label>
                <input type="date" id="preferred_start_date" name="preferred_start_date"
                    class="w-full p-2 border rounded-lg">
            </div>
            <div class="mb-4">
                <label for="preferred_end_date" class="block text-gray-700 font-bold mb-2">Preferred End Date:</label>
                <input type="date" id="preferred_end_date" name="preferred_end_date" class="w-full p-2 border rounded-lg">
            </div>
            <div class="mb-4">
                <label for="gender" class="block text-gray-700 font-bold mb-2">Gender:</label>
                <select id="gender" name="gender" class="w-full p-2 border rounded-lg">
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="traveling_with_family" class="block text-gray-700 font-bold mb-2">Traveling with Family (Yes /
                    No):</label>
                <select id="traveling_with_family" name="traveling_with_family" class="w-full p-2 border rounded-lg">
                    <option value="">Select Yes/No</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>

            <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Preferred Specialty For Rotation</h2>
            <div class="mb-4">
                <label for="preferred_specialty_option1" class="block text-gray-700 font-bold mb-2">Select Option 1
                    (mandatory):</label>
                <input type="text" id="preferred_specialty_option1" name="preferred_specialty_option1"
                    class="w-full p-2 border rounded-lg" required>
            </div>
            <div class="mb-4">
                <label for="preferred_specialty_option2" class="block text-gray-700 font-bold mb-2">Select Option 2:</label>
                <input type="text" id="preferred_specialty_option2" name="preferred_specialty_option2"
                    class="w-full p-2 border rounded-lg">
            </div>
            <div class="mb-4">
                <label for="preferred_specialty_option3" class="block text-gray-700 font-bold mb-2">Select Option 3:</label>
                <input type="text" id="preferred_specialty_option3" name="preferred_specialty_option3"
                    class="w-full p-2 border rounded-lg">
            </div>
            <div class="mb-4">
                <label for="preferred_specialty_other" class="block text-gray-700 font-bold mb-2">Other (or
                    subspecialty):</label>
                <input type="text" id="preferred_specialty_other" name="preferred_specialty_other"
                    class="w-full p-2 border rounded-lg">
            </div>

            <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Organization Coordinating Your Rotation</h2>
            <div class="mb-4">
                <label for="coordinating_organization" class="block text-gray-700 font-bold mb-2">Organization:</label>
                <select id="coordinating_organization" name="coordinating_organization"
                    class="w-full p-2 border rounded-lg">
                    <option value="World Medical Mission">World Medical Mission</option>
                    <option value="Inmed">Inmed</option>
                    <option value="SIMPACT">SIMPACT</option>
                    <option value="AIM">AIM</option>
                    <option value="None">None, requesting GME Coordination (charge of 15,000 KES for GME coordination)
                    </option>
                </select>
            </div>

            <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Referee Information</h2>
            <div class="mb-4">
                <label for="referee1_name" class="block text-gray-700 font-bold mb-2">Name of 1st Referee:</label>
                <input type="text" id="referee1_name" name="referee1_name" class="w-full p-2 border rounded-lg">
            </div>
            <div class="mb-4">
                <label for="referee1_email" class="block text-gray-700 font-bold mb-2">Email Address of 1st Referee:</label>
                <input type="email" id="referee1_email" name="referee1_email" class="w-full p-2 border rounded-lg">
            </div>
            <div class="mb-4">
                <label for="referee2_name" class="block text-gray-700 font-bold mb-2">Name of 2nd Referee:</label>
                <input type="text" id="referee2_name" name="referee2_name" class="w-full p-2 border rounded-lg">
            </div>
            <div class="mb-4">
                <label for="referee2_email" class="block text-gray-700 font-bold mb-2">Email Address of 2nd Referee:</label>
                <input type="email" id="referee2_email" name="referee2_email" class="w-full p-2 border rounded-lg">
            </div>

            <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Short Answer Questions</h2>
            <div class="mb-4">
                <label for="goals" class="block text-gray-700 font-bold mb-2">1. What are your goals for participating in
                    the Kijabe Elective Rotation?</label>
                <textarea id="goals" name="goals" class="w-full p-2 border rounded-lg"></textarea>
            </div>
            <div class="mb-4">
                <label for="prior_experience" class="block text-gray-700 font-bold mb-2">2. What prior experience or
                    exposure have you had working in low-resource settings?</label>
                <textarea id="prior_experience" name="prior_experience" class="w-full p-2 border rounded-lg"></textarea>
            </div>
            <div class="mb-4">
                <label for="future_plans" class="block text-gray-700 font-bold mb-2">3. How do you anticipate incorporating
                    global health activities into your future plans?</label>
                <textarea id="future_plans" name="future_plans" class="w-full p-2 border rounded-lg"></textarea>
            </div>
            <div class="mb-4">
                <label for="faith_practice" class="block text-gray-700 font-bold mb-2">4. How do you incorporate faith
                    practice into your care, if applicable?</label>
                <textarea id="faith_practice" name="faith_practice" class="w-full p-2 border rounded-lg"></textarea>
            </div>
            <div class="mb-4">
                <label for="additional_info" class="block text-gray-700 font-bold mb-2">5. Do you have any questions or
                    anything else you would like us to know?</label>
                <textarea id="additional_info" name="additional_info" class="w-full p-2 border rounded-lg"></textarea>
            </div>

            <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Document Uploads</h2>
            <div class="mb-4">
                <label for="passport_biodata_page" class="block text-gray-700 font-bold mb-2">Passport Bio-data
                    Page:</label>
                <input type="file" id="passport_biodata_page" name="passport_biodata_page"
                    class="w-full p-2 border rounded-lg">
            </div>
            <div class="mb-4">
                <label for="academic_professional_certificate" class="block text-gray-700 font-bold mb-2">Certified Academic
                    and Professional Certificate:</label>
                <input type="file" id="academic_professional_certificate" name="academic_professional_certificate"
                    class="w-full p-2 border rounded-lg">
            </div>
            <div class="mb-4">
                <label for="curriculum_vitae" class="block text-gray-700 font-bold mb-2">Curriculum Vitae (CV):</label>
                <input type="file" id="curriculum_vitae" name="curriculum_vitae" class="w-full p-2 border rounded-lg">
            </div>
            <div class="mb-4">
                <label for="passport_size_photo" class="block text-gray-700 font-bold mb-2">Recent Passport Size Colour
                    Photo:</label>
                <input type="file" id="passport_size_photo" name="passport_size_photo" class="w-full p-2 border rounded-lg">
            </div>
            <div class="mb-4">
                <label for="md_certificate" class="block text-gray-700 font-bold mb-2">MD Certificate
                    (Residents only):</label>
                <input type="file" id="md_certificate" name="md_certificate" class="w-full p-2 border rounded-lg">
            </div>
            <div class="mb-4">
                <label for="current_practising_licence" class="block text-gray-700 font-bold mb-2">Current practising
                    licence (Residents only):</label>
                <input type="file" id="current_practising_licence" name="current_practising_licence"
                    class="w-full p-2 border rounded-lg">
            </div>

            <button type="submit"
                class="bg-[#159ed5] text-white py-2 px-4 rounded-lg font-semibold hover:bg-[#107ba3] transition-colors duration-300">Submit
                Application</button>
        </form>
    </div>
@endsection
