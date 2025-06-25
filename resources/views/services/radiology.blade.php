<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kijabe Hospital Radiology Services</title>  
  <meta name="description" content="Kijabe Hospital offers comprehensive radiology services with state-of-the-art equipment and experienced professionals. Learn about our imaging services, including X-ray, Ultrasound, CT Scan, MRI, and Mammography.">
  <meta name="keywords" content="Kijabe Hospital, radiology services, medical imaging, X-ray, ultrasound, CT scan, MRI, mammography, diagnostics, healthcare, Kenya">
  <link rel="canonical" href="https://www.kijabehospital.org/radiology">  
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

  @include('layouts.navigation')  

  <div class="container mx-auto my-10 px-6">
    <h1 class="text-3xl font-bold text-center text-[#159ed5] mb-8">Radiology Services</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold text-[#159ed5] mb-4">X-ray</h2>
        <p>Our X-ray services provide quick and precise imaging for diagnosing fractures, infections, and other conditions.</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold text-[#159ed5] mb-4">Ultrasound</h2>
        <p>We offer ultrasound imaging for pregnancy monitoring, evaluating abdominal pain, and guiding biopsies.</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold text-[#159ed5] mb-4">CT Scan</h2>
        <p>Our CT scanner provides detailed cross-sectional images to diagnose a wide range of conditions.</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold text-[#159ed5] mb-4">MRI</h2>
        <p>Our MRI scanner creates highly detailed images of organs, tissues, and the nervous system.</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold text-[#159ed5] mb-4">Mammography</h2>
        <p>We offer digital mammography for breast cancer screening and diagnosis.</p>
      </div>
      <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold text-[#159ed5] mb-4">Fluoroscopy</h2>
        <p>This real-time imaging technique visualizes internal organs and guides procedures.</p>
      </div>
    </div>
  </div>

  @include('layouts.footer')  

</body>
</html>