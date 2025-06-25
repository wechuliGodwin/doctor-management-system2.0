@extends('layouts.app')

<style>
    /* Main Container Styling */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px;
        background-color: #f8faff; /* Soft hospital-blue background */
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.05);
        border-radius: 10px;
    }

    /* Board Members Section */
    .board-section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
        padding: 30px;
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.07);
    }

    /* Individual Board Member Styling */
    .board-member {
        position: relative;
        padding: 20px;
        border: none;
        background-color: #eef6ff; /* Light blue background for each member */
        border-radius: 8px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .board-member:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .board-position {
        font-weight: bold;
        color: #007bff;
        text-transform: uppercase;
        font-size: 0.85em;
        margin-bottom: 10px;
        text-align: center;
    }

    .board-name {
        font-size: 1.3em;
        font-weight: 600;
        color: #333;
        text-align: center;
        margin-bottom: 15px;
    }

    /* Special Styling for Chair, Vice Chair, and Executive Director */
    .board-member.special {
        background-color: #d6eaff; /* Slightly darker blue for hierarchy */
        padding: 30px;
    }

    .board-member.special .board-position {
        font-size: 1.2em;
    }

    .board-member.special .board-name {
        font-size: 1.6em;
    }

    /* Center align text for top hierarchy */
    .board-member.special .board-position,
    .board-member.special .board-name {
        text-align: center;
    }

    /* Remove blue bar */
    .board-member::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 5px;
        background-color: transparent; /* Remove the blue bar */
    }
</style>

@section('content')
<div class="container">
    <h2 style="text-align: center; color: #007bff; margin-bottom: 40px;">Kijabe Hospital Board of Directors</h2>
    
    <div class="board-section">
        <!-- Chair -->
        <div class="board-member special">
            <div class="board-position">Chair</div>
            <div class="board-name">Joshua Tonui</div>
        </div>

        <!-- Vice Chairman -->
        <div class="board-member special">
            <div class="board-position">Vice Chairman</div>
            <div class="board-name">Rev. Peter Gathere</div>
        </div>

        <!-- Executive Director -->
        <div class="board-member special">
            <div class="board-position">Executive Director</div>
            <div class="board-name">Dr. Chege Macharia</div>
        </div>

        <!-- Other Members -->
        <div class="board-member">
            <div class="board-position">Treasurer</div>
            <div class="board-name">Mr. Tunai Kinyanguk</div>
        </div>
        <div class="board-member">
            <div class="board-position">Member</div>
            <div class="board-name">Dr. Jacob Kimote</div>
        </div>
        <div class="board-member">
            <div class="board-position">Member</div>
            <div class="board-name">Mr. Francis Ndung'u</div>
        </div>
        <div class="board-member">
            <div class="board-position">Member</div>
            <div class="board-name">Mr. Philip Njuguna</div>
        </div>
        <div class="board-member">
            <div class="board-position">Member</div>
            <div class="board-name">Justice Benjamin Mwikya</div>
        </div>
        <div class="board-member">
            <div class="board-position">Member</div>
            <div class="board-name">Dr. Lydia Okutoyi</div>
        </div>
        <div class="board-member">
            <div class="board-position">Member</div>
            <div class="board-name">Bishop Paul Manyara</div>
        </div>
        <div class="board-member">
            <div class="board-position">Member</div>
            <div class="board-name">Ms. Susan Maina</div>
        </div>
        <div class="board-member">
            <div class="board-position">Member</div>
            <div class="board-name">Dr. Watson Maina</div>
        </div>
        <div class="board-member">
            <div class="board-position">Member</div>
            <div class="board-name">Mr. David Shirk</div>
        </div>
        <div class="board-member">
            <div class="board-position">Member</div>
            <div class="board-name">Mr. James Muthua</div>
        </div>
    </div>
</div>
@endsection