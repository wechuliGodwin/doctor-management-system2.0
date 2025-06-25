<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kijabe Hospital Pharmacy Services</title>  
  <meta name="description" content="Kijabe Hospital Pharmacy provides a wide range of medications, expert advice, and convenient services to meet your healthcare needs.">
  <meta name="keywords" content="Kijabe Hospital, pharmacy services, medications, prescriptions, over-the-counter drugs, healthcare, Kenya">
  <link rel="canonical" href="https://www.kijabehospital.org/pharmacy">  
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

  @include('layouts.navigation')  

  <div class="container mx-auto my-10 px-6">
    <h1 class="text-3xl font-bold text-center text-[#159ed5] mb-8">Pharmacy Services</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold text-[#159ed5] mb-4">Prescription Filling</h2>
        <p>Accurate and efficient dispensing of prescription medications.</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold text-[#159ed5] mb-4">Over-the-Counter Medications</h2>
        <p>Wide selection of over-the-counter drugs for common ailments.</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold text-[#159ed5] mb-4">Medication Counseling</h2>
        <p>Expert advice on medication usage, side effects, and interactions.</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold text-[#159ed5] mb-4">Refill Reminders</h2>
        <p>Convenient reminders to help you stay on track with your medications.</p>
      </div>
    </div>
  </div>

  @include('layouts.footer')  

</body>
</html>