@extends('layouts.app')

@section('content')
<div class="container mx-auto my-12 p-6">
    <h1 class="text-3xl font-bold text-center text-[#159ed5] mb-8">Short Courses Application</h1>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded mb-4 w-3/4 mx-auto">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('submit.short-courses-application') }}" method="POST" class="bg-white shadow-md w-3/4 mx-auto rounded-lg p-8">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-gray-700 font-bold mb-2">Full Name</label>
            <input type="text" name="name" id="name" class="form-control border border-gray-300 focus:border-indigo-500 form-input w-full p-2 rounded-md" placeholder="Your Full Name" required>
        </div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700 font-bold mb-2">Email Address</label>
            <input type="email" name="email" id="email" class="form-control border border-gray-300 focus:border-indigo-500 form-input w-full p-2 rounded-md" placeholder="Your Email Address" required>
        </div>

        <div class="mb-4">
            <label for="phone" class="block text-gray-700 font-bold mb-2">Phone Number</label>
            <input type="text" name="phone" id="phone" class="form-control border border-gray-300 focus:border-indigo-500 form-input w-full p-2 rounded-md" placeholder="Your Phone Number" required>
        </div>

        <div class="mb-4">
            <label for="course" class="block text-gray-700 font-bold mb-2">Select Course</label>
            <select name="course" id="course" class="form-control border border-gray-300 p-2 form-select w-full rounded-md" required>
                <option value="" disabled selected>Select a Course</option>
                <option value="Basic Life Support (BLS)">Basic Life Support (BLS)</option>
                <option value="Advanced Cardiac Life Support (ACLS)">Advanced Cardiac Life Support (ACLS)</option>
                <option value="Pediatrics Advanced Life Support (PALS)">Pediatrics Advanced Life Support (PALS)</option>
                <option value="Heart Saver First Aid">Heart Saver First Aid</option>
                <option value="Palliative Care Training">Palliative Care Training</option>
                <option value="Advanced Trauma Care">Advanced Trauma Care</option>
                <option value="In-house Critical Care Training">In-house Critical Care Training</option>
                <option value="Bubble C-PAP">Bubble C-PAP (Continuous Positive Airway Pressure)</option>
                <option value="NHITC - Clinical Track">National HIV Integrated Training Curriculum (NHITC) - Clinical Track</option>
                <option value="NHITC - Psychosocial Track">National HIV Integrated Training Curriculum (NHITC) - Psychosocial Track</option>
                <option value="NHITC - Nutrition Track">National HIV Integrated Training Curriculum (NHITC) - Nutrition Track</option>
                <option value="TB in the Era of HIV">TB in the Era of HIV</option>
            </select>
        </div>

        <div class="text-center">
            <button type="submit" class="bg-[#159ed5] text-white py-2 px-6 rounded-lg text-lg font-semibold hover:bg-[#0d7ca7] transition-colors duration-300">
                Apply Now
            </button>
        </div>
    </form>
</div>
@endsection
