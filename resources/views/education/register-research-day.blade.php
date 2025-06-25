@extends('layouts.app')

@section('title', '5th Annual Research Day')

@section('content')
<!-- Video Hero Section -->
<div class="hero-section relative w-full h-screen overflow-hidden">
    <video autoplay muted loop playsinline class="absolute top-0 left-0 w-full h-full object-cover z-0">
        <source src="{{ asset('videos/researchday.mp4') }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>
    <div class="overlay absolute inset-0 flex items-center justify-center bg-gradient-to-tr from-[rgba(21,158,213,0.6)] to-[rgba(0,221,235,0.4)] z-10">
        <div class="content text-center text-white animate-hero-reveal">
            <h1 class="hero-title text-7xl md:text-8xl font-extrabold mb-6 tracking-tight leading-tight drop-shadow-[0_4px_12px_rgba(0,0,0,0.8)]">
                5th Annual Research Day
            </h1>
            <p class="hero-subtitle text-2xl md:text-3xl mb-8 font-medium italic drop-shadow-[0_3px_8px_rgba(0,0,0,0.7)]">
                Kijabe Hospital | March 21, 2025
            </p>
            <p class="deadline-notice text-xl md:text-2xl font-bold bg-[rgba(255,71,87,0.85)] px-6 py-3 rounded-full shadow-xl animate-glow-pulse drop-shadow-[0_2px_6px_rgba(0,0,0,0.6)]">
                Submission Deadline Passed
            </p>
        </div>
    </div>
</div>

<!-- Countdown Section with Image Background -->
<section class="countdown-section py-20 bg-cover bg-center bg-no-repeat relative" style="background-image: url('{{ asset('images/research1.jpg') }}');">
    <div class="overlay absolute inset-0 bg-[rgba(30,39,46,0.7)] z-0"></div>
    <div class="container mx-auto relative z-10 text-center">
        <h2 class="section-title text-5xl font-extrabold text-[#00ddeb] mb-12 animate-slide-up tracking-wider drop-shadow-[0_3px_10px_rgba(0,0,0,0.5)]">
            Countdown to Research Day
        </h2>
        <div class="countdown-grid flex justify-center gap-12 flex-wrap max-w-5xl mx-auto" id="countdown">
            <div class="time-card bg-[#159ed5] text-white p-8 rounded-2xl shadow-2xl transition-all duration-300 hover:scale-110 hover:bg-[#00ddeb] animate-fade-in">
                <span id="days" class="text-6xl font-black block drop-shadow-[0_2px_6px_rgba(0,0,0,0.4)]">00</span>
                <span class="text-xl uppercase tracking-widest mt-2 block font-semibold">Days</span>
            </div>
            <div class="time-card bg-[#159ed5] text-white p-8 rounded-2xl shadow-2xl transition-all duration-300 hover:scale-110 hover:bg-[#00ddeb] animate-fade-in" style="animation-delay: 0.2s;">
                <span id="hours" class="text-6xl font-black block drop-shadow-[0_2px_6px_rgba(0,0,0,0.4)]">00</span>
                <span class="text-xl uppercase tracking-widest mt-2 block font-semibold">Hours</span>
            </div>
            <div class="time-card bg-[#159ed5] text-white p-8 rounded-2xl shadow-2xl transition-all duration-300 hover:scale-110 hover:bg-[#00ddeb] animate-fade-in" style="animation-delay: 0.4s;">
                <span id="minutes" class="text-6xl font-black block drop-shadow-[0_2px_6px_rgba(0,0,0,0.4)]">00</span>
                <span class="text-xl uppercase tracking-widest mt-2 block font-semibold">Minutes</span>
            </div>
            <div class="time-card bg-[#159ed5] text-white p-8 rounded-2xl shadow-2xl transition-all duration-300 hover:scale-110 hover:bg-[#00ddeb] animate-fade-in" style="animation-delay: 0.6s;">
                <span id="seconds" class="text-6xl font-black block drop-shadow-[0_2px_6px_rgba(0,0,0,0.4)]">00</span>
                <span class="text-xl uppercase tracking-widest mt-2 block font-semibold">Seconds</span>
            </div>
        </div>
        <p id="event-message" class="event-message text-4xl text-[#feca57] mt-12 font-extrabold hidden animate-bounce-in drop-shadow-[0_3px_10px_rgba(0,0,0,0.5)]">
            Research Day Has Arrived!
        </p>
    </div>
</section>

<!-- Program Section -->
<section class="program-section py-24 bg-[#f1f2f6] text-center">
    <div class="container mx-auto">
        <h2 class="section-title text-5xl font-extrabold text-[#159ed5] mb-8 animate-slide-up tracking-wider drop-shadow-[0_3px_10px_rgba(0,0,0,0.3)]">
            Event Program
        </h2>
        <p class="text-2xl text-[#2d3436] mb-12 animate-fade-in max-w-3xl mx-auto font-medium">
            Discover the full schedule for Research Day 2025.
        </p>
        <a href="{{ asset('researchdayprogram.pdf') }}" target="_blank" class="program-button inline-flex items-center bg-[#00ddeb] text-[#1e272e] font-bold py-5 px-12 rounded-full shadow-xl transition-all duration-400 hover:bg-[#ff4757] hover:text-white hover:scale-105 hover:shadow-2xl animate-pulse">
            <i class="fas fa-file-pdf mr-4 text-2xl"></i> View Program (PDF)
        </a>
    </div>
</section>

<style>
    /* Global Variables & Animations */
    :root {
        --primary-color: #159ed5; /* Kijabe Blue */
        --secondary-color: #00ddeb; /* Bright Cyan */
        --accent-color: #ff4757; /* Vivid Red */
        --highlight-color: #feca57; /* Golden Yellow */
        --dark-bg: #1e272e; /* Deep Slate */
        --light-bg: #f1f2f6; /* Soft Gray */
        --text-dark: #2d3436;
        --text-light: #ffffff;
    }

    @keyframes heroReveal {
        0% { opacity: 0; transform: translateY(60px) scale(0.9); }
        100% { opacity: 1; transform: translateY(0) scale(1); }
    }

    @keyframes slideUp {
        0% { opacity: 0; transform: translateY(40px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeIn {
        0% { opacity: 0; }
        100% { opacity: 1; }
    }

    @keyframes glowPulse {
        0%, 100% { box-shadow: 0 0 12px var(--accent-color), 0 0 20px rgba(255, 71, 87, 0.4); }
        50% { box-shadow: 0 0 20px var(--accent-color), 0 0 35px rgba(255, 71, 87, 0.6); }
    }

    @keyframes bounceIn {
        0% { opacity: 0; transform: scale(0.7); }
        60% { opacity: 1; transform: scale(1.15); }
        100% { opacity: 1; transform: scale(1); }
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Poppins', sans-serif;
        line-height: 1.6;
        overflow-x: hidden;
    }

    /* Hero Section with Video */
    .hero-section {
        position: relative;
    }

    video {
        min-width: 100%;
        min-height: 100%;
    }

    .overlay {
        z-index: 1;
    }

    .content {
        animation: heroReveal 1.8s ease forwards;
    }

    .hero-title {
        font-weight: 900;
    }

    .hero-subtitle {
        font-weight: 500;
    }

    .deadline-notice {
        font-weight: 700;
    }

    /* Countdown Section */
    .countdown-section {
        background-attachment: fixed; /* Parallax effect */
    }

    .section-title {
        font-weight: 900;
    }

    .time-card {
        min-width: 140px;
        background: linear-gradient(135deg, var(--primary-color), #117ea8);
        border: 2px solid rgba(255, 255, 255, 0.15);
    }

    .time-card:hover {
        background: var(--secondary-color);
        border-color: rgba(255, 255, 255, 0.35);
    }

    .event-message {
        font-weight: 900;
    }

    /* Program Section */
    .program-button {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        font-weight: 700;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .hero-title {
            font-size: 6rem;
        }

        .hero-subtitle {
            font-size: 2rem;
        }

        .deadline-notice {
            font-size: 1.5rem;
        }

        .section-title {
            font-size: 4rem;
        }

        .time-card {
            min-width: 120px;
            padding: 1.5rem;
        }

        .time-card span:first-child {
            font-size: 4.5rem;
        }
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 4.5rem;
        }

        .hero-subtitle {
            font-size: 1.75rem;
        }

        .deadline-notice {
            font-size: 1.25rem;
            padding: 0.5rem 1.5rem;
        }

        .section-title {
            font-size: 3.5rem;
        }

        .countdown-grid {
            gap: 1.5rem;
        }

        .time-card {
            min-width: 100px;
            padding: 1.25rem;
        }

        .time-card span:first-child {
            font-size: 3.5rem;
        }

        .time-card span:last-child {
            font-size: 1rem;
        }

        .program-button {
            padding: 1rem 2.5rem;
            font-size: 1.1rem;
        }
    }

    @media (max-width: 480px) {
        .hero-title {
            font-size: 3rem;
        }

        .hero-subtitle {
            font-size: 1.25rem;
        }

        .deadline-notice {
            font-size: 1rem;
            padding: 0.4rem 1rem;
        }

        .section-title {
            font-size: 2.5rem;
        }

        .time-card {
            min-width: 80px;
            padding: 1rem;
        }

        .time-card span:first-child {
            font-size: 2.5rem;
        }

        .time-card span:last-child {
            font-size: 0.9rem;
        }

        .event-message {
            font-size: 2rem;
        }

        .program-button {
            padding: 0.75rem 2rem;
            font-size: 1rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Countdown Logic
        const researchDay = new Date('2025-03-21T07:00:00+03:00').getTime();
        const daysEl = document.getElementById('days');
        const hoursEl = document.getElementById('hours');
        const minutesEl = document.getElementById('minutes');
        const secondsEl = document.getElementById('seconds');
        const countdownEl = document.getElementById('countdown');
        const eventMessageEl = document.getElementById('event-message');

        function updateCountdown() {
            const now = new Date().getTime();
            const distance = researchDay - now;

            if (distance <= 0) {
                countdownEl.classList.add('hidden');
                eventMessageEl.classList.remove('hidden');
                clearInterval(timer);
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            daysEl.textContent = String(days).padStart(2, '0');
            hoursEl.textContent = String(hours).padStart(2, '0');
            minutesEl.textContent = String(minutes).padStart(2, '0');
            secondsEl.textContent = String(seconds).padStart(2, '0');
        }

        updateCountdown();
        const timer = setInterval(updateCountdown, 1000);
    });
</script>
@endsection