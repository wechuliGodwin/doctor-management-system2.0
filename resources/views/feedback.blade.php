@extends('layouts.app')

@section('content')
<div class="container mx-auto my-8 p-4">
    <h1 class="text-3xl font-bold text-center mb-8">Patient Satisfaction Survey</h1>

    <p class="text-lg text-gray-700 text-center mb-6">
        Your continuing honest feedback and support help us provide excellent compassionate healthcare to God's glory.
    </p>

    <form action="{{ route('feedback.store') }}" method="POST" class="w-full max-w-3xl mx-auto">
        @csrf 

        <div class="mb-4">
            <label for="visit_date" class="block text-gray-700 font-medium mb-2">Date of Visit:</label>
            <input type="date" name="visit_date" id="visit_date" class="w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-[#159ed5]">
        </div>

        <div class="mb-4">
            <label for="referral_source" class="block text-gray-700 font-medium mb-2">Who referred you to Kijabe Hospital?</label>
            <select name="referral_source" id="referral_source" class="w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-[#159ed5]">
                <option value="">-- Select an option --</option>
                <option value="friends">Friends</option>
                <option value="relatives">Relatives</option>
                <option value="doctor">Doctor</option>
                <option value="media">Media (Newspaper, TV, Radio, Billboard)</option>
                <option value="other">Other</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="referral_source_other" class="block text-gray-700 font-medium mb-2">If Other, please specify:</label>
            <input type="text" name="referral_source_other" id="referral_source_other" class="w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-[#159ed5]">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">On a scale of 1 (very poor) - 10 (Excellent), how do you rate your overall experience at the hospital?</label>
            <div class="flex items-center space-x-4">
                @for ($i = 1; $i <= 10; $i++)
                    <div>
                        <input type="radio" name="overall_rating" id="rating-{{ $i }}" value="{{ $i }}" class="mr-2">
                        <label for="rating-{{ $i }}">{{ $i }}</label>
                    </div>
                @endfor
            </div>
        </div>

        <div class="mb-4">
            <label for="experience_reason" class="block text-gray-700 font-medium mb-2">Please explain the reason for your rating:</label>
            <textarea name="experience_reason" id="experience_reason" rows="5" class="w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-[#159ed5]"></textarea>
        </div>

        <div class="mb-4">
            <p class="block text-gray-700 font-medium mb-2">Please select appropriately what best describes your experience (only where you were served) in the following service points:</p>
            <table class="w-full border border-gray-300">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border-b border-gray-300">Service Point</th>
                        <th class="px-4 py-2 border-b border-gray-300">Excellent</th>
                        <th class="px-4 py-2 border-b border-gray-300">Good</th>
                        <th class="px-4 py-2 border-b border-gray-300">Average</th>
                        <th class="px-4 py-2 border-b border-gray-300">Poor</th>
                        <th class="px-4 py-2 border-b border-gray-300">Very Poor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (['Security', 'Customer care reception', 'Registration', 'Triage', 'Doctor(s) care', 'Nursing care', 'Wards (inpatient)', 'Operation room', 'Physiotherapy', 'Nutrition', 'Pathology', 'Eye clinic', 'CCC', 'Billing Cashier', 'Laboratory', 'Ultrasound/X-ray/ CT Scan', 'Admissions office', 'Pharmacy', 'Spiritual care', 'Discharge process'] as $service)
                        <tr>
                            <td class="px-4 py-2 border-b border-gray-300">{{ $service }}</td>
                            @for ($i = 5; $i >= 1; $i--) 
                                <td class="px-4 py-2 border-b border-gray-300 text-center">
                                    <input type="radio" name="service_rating[{{ $service }}]" value="{{ $i }}">
                                </td>
                            @endfor
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mb-4">
            <label for="case_handling_opinion" class="block text-gray-700 font-medium mb-2">Please let us know your honest opinion on how your case was handled at our facility:</label>
            <textarea name="case_handling_opinion" id="case_handling_opinion" rows="5" class="w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-[#159ed5]"></textarea>
        </div>

        <div class="mb-4">
            <p class="block text-gray-700 font-medium mb-2">Please complete this part about your experience at Kijabe Hospital:</p>
            <table class="w-full border border-gray-300">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border-b border-gray-300">Statement</th>
                        <th class="px-4 py-2 border-b border-gray-300">Strongly Agree</th>
                        <th class="px-4 py-2 border-b border-gray-300">Agree</th>
                        <th class="px-4 py-2 border-b border-gray-300">Neutral</th>
                        <th class="px-4 py-2 border-b border-gray-300">Disagree</th>
                        <th class="px-4 py-2 border-b border-gray-300">Strongly Disagree</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ([
                        'My concerns were well addressed',
                        'Received enough attention/care from the nurses',
                        'Was involved in decision making regarding my care at the facility',
                        'Nurses paid attention when I needed help',
                        'Doctors paid attention when I needed help',
                        'Doctors and Nurses communicated in a language I could understand',
                        'Would recommend Kijabe Hospital to my Friends and loved ones'
                    ] as $statement)
                        <tr>
                            <td class="px-4 py-2 border-b border-gray-300">{{ $statement }}</td>
                            @for ($i = 5; $i >= 1; $i--)
                                <td class="px-4 py-2 border-b border-gray-300 text-center">
                                    <input type="radio" name="statement_agreement[{{ $statement }}]" value="{{ $i }}">
                                </td>
                            @endfor
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    <div class="mb-4">
            <label for="improvement_suggestions" class="block text-gray-700 font-medium mb-2">Do you have any suggestions on how we could improve your experience?</label>
            <textarea name="improvement_suggestions" id="improvement_suggestions" rows="5" class="w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-[#159ed5]"></textarea>
        </div>

        <div class="mb-4">
            <p class="block text-gray-700 font-medium mb-2">Would you want us to contact you in the future about your feedback?</p>
            <div class="flex items-center space-x-4">
                <div>
                    <input type="radio" name="future_contact" id="future_contact_yes" value="1" class="mr-2">
                    <label for="future_contact_yes">Yes</label>
                </div>
                <div>
                    <input type="radio" name="future_contact" id="future_contact_no" value="0" class="mr-2">
                    <label for="future_contact_no">No</label>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <label for="full_name" class="block text-gray-700 font-medium mb-2">Your Full Name (Optional):</label>
            <input type="text" name="full_name" id="full_name" class="w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-[#159ed5]">
        </div>

        <div class="mb-4">
            <label for="mobile_number" class="block text-gray-700 font-medium mb-2">Mobile Number:</label>
            <input type="tel" name="mobile_number" id="mobile_number" class="w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-[#159ed5]">
        </div>

        <div class="text-center">
            <button type="submit" class="bg-[#159ed5] hover:bg-[#1279a8] text-white font-medium py-2 px-6 rounded-md">Submit Feedback</button>
        </div>
    </form>
</div>
<style>
    /* Form Container */
.container {
  max-width: 90%; /* Adjust max-width for responsiveness */
  margin: 0 auto;
  padding: 2rem;
}

/* Form Elements */
input[type="date"],
select,
input[type="text"],
textarea {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 0.25rem;
  box-sizing: border-box;
}

/* Table Styling */
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 1rem;
}

th, td {
  border: 1px solid #ddd;
  padding: 0.5rem;
  text-align: left;
}

th {
  background-color: #f5f5f5;
}

/* Radio Buttons and Labels */
input[type="radio"] {
  margin-right: 0.5rem;
}

label {
  display: block;
  margin-bottom: 0.5rem;
}

/* Submit Button */
button[type="submit"] {
  background-color: #159ed5;
  color: white;
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 0.25rem;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

button[type="submit"]:hover {
  background-color: #1279a8;
}

/* Responsive Design */
@media (max-width: 640px) {
  table {
    display: block; /* Make table scrollable on small screens */
    overflow-x: auto;
  }
}

</style>
@endsection