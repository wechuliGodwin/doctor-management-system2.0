@extends('layouts.auth')

@section('content')
<div class="container mx-auto p-6 max-w-3xl">
    <h1 class="text-2xl font-bold mb-6 text-159ed5"><i class="fas fa-calendar-check text-159ed5 mr-2"></i>Book a Service: {{ $service->name }}</h1>

    <!-- Booking Form -->
    <form id="bookingForm" action="{{ route('services.book', $service->id) }}" method="POST" class="bg-blue-50 p-6 rounded-lg shadow-md space-y-4 w-full md:w-4/5 lg:w-3/4 mx-auto">
        @csrf
        <!-- Service Cost -->
        <div>
            <label class="text-gray-800 font-semibold">Service Cost</label>
            <p class="text-lg text-gray-900">{{ number_format($service->cost, 2) }} KSH</p>
        </div>

        <!-- Platform Input -->
        <div>
            <label for="platform" class="text-gray-800 font-semibold"><i class="fas fa-globe text-159ed5 mr-2"></i>Platform</label>
            <input type="text" id="platform" name="platform" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-159ed5" placeholder="Choose Text, Video, Callback, Whatsapp" required>
        </div>

        <!-- Description -->
        <div>
            <label for="description" class="text-gray-800 font-semibold"><i class="fas fa-info-circle text-159ed5 mr-2"></i>Description</label>
            <input type="text" id="description" name="description" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-159ed5" placeholder="Briefly describe your issue" required>
        </div>

        <!-- Appointment Date -->
        <div>
            <label for="appointment_date" class="text-gray-800 font-semibold"><i class="fas fa-calendar-alt text-159ed5 mr-2"></i>Appointment Date</label>
            <input type="text" id="appointment_date" name="appointment_date" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-159ed5" placeholder="Select a date" required>
        </div>

        <!-- Payment Section -->
        <div class="text-lg font-semibold text-gray-800">Total: {{ number_format($service->cost, 2) }} KSH</div>
        <button type="button" onclick="openPaymentModal()" class="bg-159ed5 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mt-4 w-full">Pay Now</button>

        <!-- Confirmation Section -->
        <div id="confirmBookingSection" class="hidden">
            <label class="text-gray-800 font-semibold">M-Pesa Payment Code</label>
            <input type="text" id="displayedMpesaCode" name="mpesa_code" readonly class="w-full px-3 py-2 border rounded-lg bg-gray-100 text-gray-800" placeholder="No payment yet">
            <button type="submit" id="confirmBookingBtn" disabled class="bg-gray-500 text-white font-bold py-2 px-4 rounded mt-4 w-full">Confirm Booking</button>
        </div>
    </form>
</div>

<!-- Payment Modal -->
<div id="paymentModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 hidden justify-center items-center">
    <div class="bg-white rounded-lg shadow-lg p-6 w-80">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Payment Options</h2>
        <p class="mb-4">Total: <span class="font-bold">{{ number_format($service->cost, 2) }} KSH</span></p>
        <button onclick="triggerStkPush()" class="bg-blue-200 hover:bg-blue-200 text-white font-bold py-2 px-4 rounded mb-2 w-full" disabled>STK Push</button>
        <button onclick="showPaybillForm()" class="bg-159ed5 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded w-full">Paybill</button>
        <button onclick="closePaymentModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mt-4 w-full">Cancel</button>
    </div>
</div>

<!-- Paybill Form Modal -->
<div id="paybillForm" class="fixed inset-0 bg-gray-800 bg-opacity-75 hidden justify-center items-center">
    <div class="bg-white rounded-lg shadow-lg p-6 w-80">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Enter M-Pesa Code</h2>
        <form id="paybillFormSubmission" onsubmit="confirmPaybillPayment(event)">
            <p><strong>Paybill Number:</strong> 512900</p>
            <p class="mb-4"><strong>Account Number:</strong> Your First Name</p>
            <label for="mpesa_code" class="text-gray-800 font-semibold">M-Pesa Code</label>
            <input type="text" id="mpesa_code" name="mpesa_code" class="w-full px-3 py-2 border rounded-lg mb-4 focus:outline-none focus:ring-2 focus:ring-159ed5" placeholder="Enter M-Pesa Code" required>
            <button type="submit" class="bg-159ed5 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded w-full">Confirm Payment</button>
        </form>
        <button onclick="closePaybillForm()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mt-4 w-full">Cancel</button>
    </div>
</div>

<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- Modal, Form, and Flatpickr Scripts -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    // Initialize Flatpickr for appointment date
    flatpickr("#appointment_date", {
        dateFormat: "Y-m-d",
        minDate: "today",
        enable: [
            function(date) {
                // Enable only Tuesdays (2), Thursdays (4), and Fridays (5)
                return [2, 4, 5].includes(date.getDay());
            }
        ],
        onChange: function(selectedDates, dateStr, instance) {
            if (selectedDates.length > 0) {
                console.log("Selected appointment date:", dateStr);
            }
        }
    });

    // Existing modal and form scripts
    function openPaymentModal() { toggleModal('paymentModal', true); }
    function closePaymentModal() { toggleModal('paymentModal', false); }
    function showPaybillForm() { closePaymentModal(); toggleModal('paybillForm', true); }
    function closePaybillForm() { toggleModal('paybillForm', false); }
    function triggerStkPush() {
        alert('STK Push sent. Check your phone to complete payment.');
        setTimeout(() => activateConfirmBooking('STK-123456'), 3000);
    }
    function confirmPaybillPayment(event) {
        event.preventDefault();
        const mpesaCode = document.getElementById('mpesa_code').value;
        if (mpesaCode) {
            activateConfirmBooking(mpesaCode);
            closePaybillForm();
        }
    }
    function activateConfirmBooking(mpesaCode) {
        document.getElementById('displayedMpesaCode').value = mpesaCode;
        document.getElementById('confirmBookingBtn').disabled = false;
        document.getElementById('confirmBookingBtn').classList.replace('bg-gray-500', 'bg-159ed5');
        document.getElementById('confirmBookingSection').classList.remove('hidden');
    }
    function toggleModal(modalId, show) {
        document.getElementById(modalId).classList.toggle('hidden', !show);
        document.getElementById(modalId).classList.toggle('flex', show);
    }
</script>

<style>
    /* Custom Theme Color */
    .text-159ed5 { color: #159ed5; }
    .bg-159ed5 { background-color: #159ed5; }
    .flatpickr-day {
        border: 1px solid #d1d5db !important; /* Light gray border for all dates */
        border-radius: 0 !important; /* Square borders */
    }
    .flatpickr-day.disabled {
        color: #666 !important;
        background: #e0e0e0 !important;
        cursor: not-allowed !important;
    }
</style>
@endsection
