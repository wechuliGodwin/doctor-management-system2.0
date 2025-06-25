<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Staff Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"> 
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f0f0f0; 
      color: #333;
      margin: 0;
      padding: 0;
      display: flex;
      flex-direction: column;
      min-height: 100vh; 
    }
    .navbar {
      background-color: #159ed5;
      color: white;
      padding: 10px;
      text-align: center;
    }
    .container {
      padding: 20px;
      flex: 1; 
    }
    .info-box {
      background-color: #fff;
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      text-align: center; 
      display: flex;
      flex-direction: column; 
      justify-content: center; 
    }
    .info-box h2 {
      color: #159ed5; 
      margin-bottom: 10px;
    }
    .info-box p {
      font-size: 1.2rem; 
      font-weight: bold;
    }
    .footer {
      background-color: #333; 
      color: white;
      text-align: center;
      padding: 10px 0; 
      margin-top: auto; 
    }
  </style>
</head>
<body>

  @include('layouts.menu')

  <div class="navbar"></div>

  <div class="container mx-auto"> 
    <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">Welcome to the Staff Dashboard</h1> 

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
      <div class="info-box bg-blue-100">
        <h2 class="text-lg">Total Appointments</h2> 
        <p>120</p> 
      </div>
      <div class="info-box bg-green-100">
        <h2 class="text-lg">Patients Admitted</h2> 
        <p>35</p> 
      </div>
      <div class="info-box bg-yellow-100">
        <h2 class="text-lg">Pending Lab Results</h2> 
        <p>8</p> 
      </div>
      <div class="info-box bg-red-100">
        <h2 class="text-lg">Critical Patients</h2> 
        <p>2</p> 
      </div>
    </div>

    <div class="card bg-white border border-gray-300 rounded-lg shadow-md p-6"> 
      <h2 class="text-xl font-bold text-gray-800 mb-4">Recent Activities</h2>
      <p>Details of recent actions and tasks.</p> 
    </div>
  </div> 

  <footer class="footer">
    <p>&copy; 2024 Kijabe Hospital. All rights reserved.</p>
  </footer> 

</body>
</html>