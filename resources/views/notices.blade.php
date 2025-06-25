@extends('layouts.app')

<style>
    /* Main Container Styling */
    .container {
        max-width: 900px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f8faff; /* Soft hospital-blue background */
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.05);
        border-radius: 8px;
    }

    /* Search and Filter Section */
    .search-section {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-bottom: 25px;
    }
    .search-section input {
        max-width: 65%;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #159ed5;
    }
    .search-section button {
        padding: 10px 20px;
        background-color: #007bff; /* Hospital-theme blue */
        color: #fff;
        border: none;
        border-radius: 5px;
    }

    /* Navigation for Calendar View */
    .navigation-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .navigation-section .btn {
        font-weight: 500;
    }
    .navigation-section span {
        font-size: 1.2em;
        font-weight: bold;
        color: #007bff;
    }

    /* Notices Section */
    .notices-section {
        background-color: #ffffff;
        padding: 15px;
        border-radius: 8px;
        text-align: center;
        margin-bottom: 30px;
        color: #555;
    }

    /* Past Events Section Title */
    h3 {
        text-align: center;
        color: #007bff;
        font-weight: bold;
        margin-bottom: 25px;
    }

    /* Notice Item Styling */
    .event-item {
        display: flex;
        align-items: start;
        padding: 20px 0;
        border-bottom: 1px solid #e0e0e0;
        gap: 15px;
    }

    /* Date Section Styling */
    .date-section {
        text-align: center;
        width: 80px;
    }
    .date-section .month {
        font-size: 1.2em;
        color: #007bff;
        font-weight: bold;
    }
    .date-section .day {
        font-size: 2em;
        font-weight: bold;
        color: #333;
    }
    .date-section .year {
        color: #888;
    }

    /* Event Details Styling */
    .event-details {
        max-width: 600px;
    }
    .event-details p {
        color: #555;
        margin-bottom: 5px;
    }
    .event-details h4 {
        font-size: 1.3em;
        font-weight: bold;
        color: #333;
    }

    /* Image Placeholder */
    .event-image {
        width: 100px;
        height: 100px;
        border-radius: 5px;
        object-fit: cover;
        border: 1px solid #e0e0e0;
        cursor: pointer;
        transition: transform 0.3s ease; /* Smooth transition for zoom effect */
    }

    /* Hover effect: Image Enlarge */
    .event-image:hover {
        transform: scale(1.2); /* Enlarges the image on hover */
    }

    /* Modal Styling */
    .modal {
        display: none; /* Hidden by default */
        position: fixed;
        z-index: 9999;
        padding-top: 60px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.8); /* Dark background */
        transition: opacity 0.3s ease; /* Smooth transition for modal visibility */
    }

    .modal-content {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        border-radius: 8px;
    }

    .close {
        position: absolute;
        top: 20px;
        right: 35px;
        color: #fff;
        font-size: 40px;
        font-weight: bold;
        cursor: pointer;
    }
</style>

@section('content')
<div class="container">
    <!-- Search and Filter Events Section -->
    <div class="search-section">
        <input type="text" class="form-control" placeholder="Search for notices or events">
        <button class="btn btn-primary">Find Notices</button>
    </div>

    <!-- Navigation for Calendar View -->
    <div class="navigation-section">
        <div>
            <button class="btn btn-outline-secondary">Today</button>
            <span>Upcoming Notices</span>
        </div>
        <div>
            <button class="btn btn-link">List</button>
            <button class="btn btn-link">Month</button>
            <button class="btn btn-link">Day</button>
        </div>
    </div>

    <!-- Notices Section -->
    <div class="notices-section">
        <p class="text-muted">Upcoming notices.</p>
    </div>

    <!-- Past Events Section -->
    <h3>Recent Hospital Notices</h3>
    
    <!-- First Notice Item -->
  <div class="event-item">
        <div class="date-section">
            <div class="month">NOV</div>
            <div class="day">26</div>
            <div class="year">2024</div>
        </div>
    
        <img src="https://kijabehospital.org/images/chronicpain.jpeg" alt="Event Image" class="event-image" onclick="openModal(this.src)">
    
        <div class="event-details">
            <p class="text-muted">November 16, 2024 </p>
            <h4>Chronic Pain clinic</h4>
            <p>We are offering specialized care for Chronic Pain clinic!</p>
        </div>
    </div> 








<div class="event-item">
        <div class="date-section">
            <div class="month">NOV</div>
            <div class="day">19</div>
            <div class="year">2024</div>
        </div>
    
        <img src="https://kijabehospital.org/images/gastroentologyclinic.jpeg" alt="Event Image" class="event-image" onclick="openModal(this.src)">
    
        <div class="event-details">
            <p class="text-muted">November 19, 2024 </p>
            <h4>Gastroenterology Clinic Services</h4>
            <p>We offer specialized care for liver disorders, pancreatic disorders, and gastrointestinal disorders. Join us for consultations and expert treatment at Main Hospital on the first Monday of each month and at Westland Medical Centre on the third Monday of each month</p>
        </div>
    </div>





    <div class="event-item">
        <div class="date-section">
            <div class="month">NOV</div>
            <div class="day">14</div>
            <div class="year">2024</div>
        </div>
        
        <img src="https://kijabehospital.org/notice/noticediabetes.jpeg" alt="Event Image" class="event-image" onclick="openModal(this.src)">
        
        <div class="event-details">
            <p class="text-muted">November 14, 2024 @ 8:00 pm</p>
            <h4>One of Our Staff Attending a Show on TV47 on Diabetes Awareness</h4>
            <p>Our health expert will be featured on TV47 discussing the importance of diabetes awareness and management. Tune in to learn more about preventing and managing diabetes.</p>
        </div>
    </div>

<div class="event-item">
        <div class="date-section">
            <div class="month">NOV</div>
            <div class="day">10</div>
            <div class="year">2024</div>
        </div>

        <img src="https://kijabehospital.org/notice/noticeaccounting.jpeg" alt="Event Image" class="event-image" onclick="openModal(this.src)">

        <div class="event-details">
            <p class="text-muted">November 10, 2024</p>
            <h4>Happy International Accounting Day</h4>
            <p>We celebrate our dedicated accounting professionals on this special day! Thank you for all your hard work in keeping our financial operations running smoothly.</p>
        </div>
    </div>

    <!-- Second Notice Item -->
    <div class="event-item">
        <div class="date-section">
            <div class="month">NOV</div>
            <div class="day">04</div>
            <div class="year">2024</div>
        </div>

        <img src="https://kijabehospital.org/notice/noticemechandise.jpeg" alt="Event Image" class="event-image" onclick="openModal(this.src)">

        <div class="event-details">
            <p class="text-muted">November 04, 2024 </p>
            <h4>Branded Merchandise Now in Stock</h4>
            <p>We are excited to announce that our branded hospital merchandise is now available for purchase. Come grab an items in cooprates office MEB SECOND FLOOR!</p>
        </div>
    </div>

    <!-- Third Notice Item -->
    <div class="event-item">
        <div class="date-section">
            <div class="month">NOV</div>
            <div class="day">04</div>
            <div class="year">2024</div>
        </div>

        <img src="https://kijabehospital.org/notice/noticecancercentre.jpeg" alt="Event Image" class="event-image" onclick="openModal(this.src)">

        <div class="event-details">
            <p class="text-muted">November 04, 2024 </p>
            <h4>Cancer Centre Now Open</h4>
            <p>We are proud to announce the opening of our state-of-the-art Cancer Centre, offering comprehensive care for cancer patients.</p>
        </div>
    </div>

    <!-- Fourth Notice Item -->
<!-- Modal Structure -->
<div id="myModal" class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    <img class="modal-content" id="modalImage">
</div>

<script>
    // Open the Modal and set image source
    function openModal(src) {
        document.getElementById('modalImage').src = src;
        document.getElementById('myModal').style.display = "block";
    }

    // Close the Modal
    function closeModal() {
        document.getElementById('myModal').style.display = "none";
    }

    // Close Modal if clicked outside of the image
    window.onclick = function(event) {
        if (event.target == document.getElementById('myModal')) {
            closeModal();
        }
    }
</script>

@endsection
