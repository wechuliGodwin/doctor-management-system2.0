@extends('layouts.app')

@section('content')
<div class="container mx-auto my-8 p-4">
    <h1 class="text-3xl font-bold text-center mb-8">Nutrition Care Services</h1>

    <p class="text-lg text-gray-700 mb-4">
    The Nutrition Department at AIC Kijabe Hospital consists of a dedicated team of nutritionists/dietitians who provide Medical Nutrition Therapy for both adult and pediatric patients/clients.  
Our trained registered nutritionists/dietitians provide evidence-based information on food and nutrition related issues. This is either through the promotion of good eating habits or in the dietetic treatment of diseases and disorders on the nutritional needs of patients to promote optimal nutrition, health and wellness. 
    </p>

    <p class="text-lg text-gray-700 mb-4">
    This is done through Individual nutrition assessment, nutrition education for disease prevention and nutrition counseling for chronic conditions for all age groups which are essential components of a comprehensive disease management and wellness program.
    The nutritionists/dietitians also work together with other healthcare professionals in multi-disciplinary teams. They are also involved in food services in the development of therapeutic diets, research and community outreach services. 
    </p>

    <p class="text-lg text-gray-700 mb-4">
        We offer comprehensive nutrition services for a variety of needs, including chronic disease management, weight management, pediatric nutrition, and more. Our goal is to empower patients with the knowledge and tools they need to make informed dietary choices and lead healthier lives.
    </p>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Our Services Include:</h2>
    <ul class="list-disc list-inside text-gray-700 mb-4 ml-8">
        <li>Individualized nutrition counseling and meal planning</li>
        <li>Dietary management for chronic conditions such as diabetes, hypertension, and heart disease</li>
        <li>Pediatric nutrition for children with special dietary needs</li>
        <li>Weight management programs</li>
        <li>Nutritional support for patients undergoing cancer treatment</li>
        <li>Education on healthy eating habits and lifestyle changes</li>
        <li>Group nutrition workshops and cooking demonstrations</li>
    </ul>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Visual Inspiration for Healthy Eating</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <img src="{{ asset('https://cdn.pixabay.com/photo/2018/06/28/15/23/berries-3504149_1280.jpg') }}" alt="Fruit Salad" class="w-full h-48 object-cover">
            <div class="p-4">
                <h3 class="text-xl font-semibold">Fruit Salad</h3>
                <p class="text-gray-700">A refreshing mix of seasonal fruits, packed with vitamins and antioxidants.</p>
            </div>
        </div>
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <img src="{{ asset('https://cdn.pixabay.com/photo/2021/06/29/04/12/whole-grains-6373178_1280.jpg') }}" alt="Whole Grains" class="w-full h-48 object-cover">
            <div class="p-4">
                <h3 class="text-xl font-semibold">Whole Grains</h3>
                <p class="text-gray-700">Whole grains like brown rice and quinoa are great sources of fiber and nutrients.</p>
            </div>
        </div>
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <img src="{{ asset('https://cdn.pixabay.com/photo/2017/02/23/15/29/oriental-2092468_1280.jpg') }}" alt="Vegetable Stir-fry" class="w-full h-48 object-cover">
            <div class="p-4">
                <h3 class="text-xl font-semibold">Vegetable Stir-fry</h3>
                <p class="text-gray-700">A colorful vegetable stir-fry loaded with vitamins and minerals.</p>
            </div>
        </div>
    </div>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Our Team</h2>
    <p class="text-lg text-gray-700 mb-4">
        Our Nutrition Care team includes registered dietitians and nutritionists who are passionate about helping patients achieve their health goals through proper nutrition. We provide expert guidance and support to help patients make sustainable dietary changes.
    </p>

    <h2 class="text-2xl font-bold text-[#159ed5] mb-4">Contact Us</h2>
    <p class="text-lg text-gray-700 mb-4">
	Our services run from Monday to Friday 8AM to 5 PM at Out-patient and 8AM to 5 PM Monday to Saturday at In-patient department.
    </p>
    <p class="text-lg text-gray-700 mb-4">
       For more information about us or to schedule a consultation, please contact us:
    </p>
     <p class="text-lg text-gray-700 mb-2">
                Phone: <a href="tel:+254709728200" class="text-[#159ed5] hover:underline">+254 (0) 709 728 200</a> 
            </p>
    <p class="text-lg text-gray-900 mb-2">
        Email: nutrition@kijabehospital.org
    </p>
    <p class="text-lg text-gray-700">
        To book an appointment or consultation online, please click <a href="{{ route('booking.show') }}" class="text-[#159ed5] hover:underline">here</a>.
    </p>
</div>
@endsection

