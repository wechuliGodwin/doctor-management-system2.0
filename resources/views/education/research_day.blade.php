
@extends('layouts.app')

@section('content')

<style>
    /* Existing guest image height */
    .guest-image {
        height: 224px !important;
    }

    /* Fade-in and slide-up for section headers */
    .animate-header {
        animation: slideUp 0.8s ease-out forwards;
    }

    /* Bounce effect for buttons on hover */
    .animate-button {
        transition: transform 0.3s ease;
    }
    .animate-button:hover {
        animation: bounce 0.5s ease infinite;
    }

    /* Card pop-in effect for guest cards */
    .animate-card {
        opacity: 0;
        transform: translateY(20px);
        animation: popIn 0.6s ease-out forwards;
    }

    /* Enhanced Glassy Countdown Styles */
    .glass-countdown {
        background: rgba(255, 255, 255, 0.15); /* Slightly more opaque for depth */
        backdrop-filter: blur(12px); /* Increased blur for smoother glass effect */
        border: 1px solid rgba(255, 255, 255, 0.25); /* Softer border */
        border-radius: 16px; /* Rounded corners for elegance */
        padding: 1.5rem 2.5rem; /* More padding for spaciousness */
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25); /* Deeper shadow for 3D effect */
        color: #ffffff; /* Pure white for contrast */
        text-align: center;
        font-family: 'Georgia', serif; /* Professional serif font */
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 10;
    }
    .glass-countdown h3 {
        font-size: 1.75rem; /* Slightly larger for prominence */
        font-weight: 700; /* Bold but refined */
        margin-bottom: 0.75rem;
        text-shadow: 0 2px 6px rgba(0, 0, 0, 0.4); /* Enhanced shadow for depth */
        letter-spacing: 0.5px; /* Subtle spacing for neatness */
    }
    .glass-countdown p {
        font-size: 1.25rem;
        margin: 0.5rem 0;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3); /* Softer text shadow */
    }
    .glass-countdown .countdown-values {
        display: flex;
        justify-content: center;
        gap: 1.5rem; /* Wider gap for clarity */
        font-size: 1.75rem; /* Larger values for emphasis */
        font-weight: 600; /* Semi-bold for balance */
        letter-spacing: 1px; /* Neat spacing */
    }

    /* Share dropdown styles */
    .share-dropdown {
        display: none;
        position: absolute;
        right: 0;
        top: 100%;
        margin-top: 0.5rem;
        background: white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        border-radius: 8px;
        padding: 0.5rem;
        z-index: 20;
    }
    .share-dropdown.active {
        display: flex;
    }

    /* New Poster Section Styles */
    .poster-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }
    .poster-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .poster-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    .poster-card h4 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #159ed5;
        margin-bottom: 0.75rem;
    }
    .poster-card p {
        font-size: 1rem;
        color: #333;
        margin-bottom: 0.5rem;
    }
    .poster-card .authors {
        font-style: italic;
        color: #666;
    }
    .poster-card .view-btn {
        background: #159ed5;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        transition: background 0.3s ease;
    }
    .poster-card .view-btn:hover {
        background: #1386b5;
    }

    /* Keyframes */
    @keyframes slideUp {
        0% { opacity: 0; transform: translateY(30px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }
    @keyframes popIn {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }

@keyframes pulseButton {
    0% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.05); opacity: 0.9; }
    100% { transform: scale(1); opacity: 1; }
}
.animate-pulse-button {
    animation: pulseButton 2s ease-in-out infinite;
}
</style>

<!-- Hero Slideshow Section -->
<div class="relative w-full h-[70vh] overflow-hidden">
    <div id="slider" class="relative w-full h-full">
        @foreach(['research1.jpg', 'research2.jpg', 'research3.jpg'] as $image)
            <div class="absolute top-0 left-0 w-full h-full bg-cover bg-center bg-no-repeat transition-opacity duration-1000 ease-in-out {{ $loop->first ? 'opacity-100' : 'opacity-0' }}" style="background-image: url('{{ asset('images/' . $image) }}');">
                <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-b from-black/70 to-black/40">
                    <div class="text-center text-white animate-fade-in px-4">
                        <h1 class="text-3xl sm:text-4xl md:text-5xl font-serif font-bold mb-4 drop-shadow-lg tracking-tight">5th Annual Research Day 2025</h1>
                        <p class="text-base sm:text-lg md:text-xl font-serif mb-6 drop-shadow-md">Ignite curiosity | Inspire innovation</p>
                        <p class="text-sm sm:text-base md:text-lg font-serif font-semibold">March 21, 2025</p>
                        <!-- Animated View Research Day Posters Button -->
                        <div class="mt-8">
                            <a href="{{ route('view-research-day-posters') }}" class="inline-block bg-[#159ed5] hover:bg-[#1386b5] text-white font-serif font-semibold py-3 px-8 rounded-full transition duration-300 shadow-md hover:shadow-lg animate-pulse-button">
                                View Research Day Posters
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>


<!-- Welcome Section with Fading Border -->
<div class="container mx-auto mt-12 mb-14 px-4 sm:px-6 lg:px-8">
    <h2 class="text-3xl sm:text-4xl md:text-5xl font-serif font-bold text-center mb-8 text-[#159ed5] animate-header">Welcome to Research Day 2025</h2>
    <div class="max-w-3xl mx-auto text-center text-gray-800 animate-fade-in font-serif bg-white p-6 rounded-lg shadow-lg border-2" style="border-image: linear-gradient(to bottom, #159ed5, transparent) 1;">
        <p class="text-base sm:text-lg md:text-xl mb-6 leading-relaxed text-justify">Join us for the 5th Annual Kijabe Research Day, a celebration of innovation and progress in healthcare. This event highlights Kijabe Hospital's commitment to enhancing care quality, advancing medical training, and leveraging local data to address Africa's health challenges.</p>
        <p class="text-base sm:text-lg md:text-xl mb-6 leading-relaxed text-justify">Discover the researchers and quality improvement leaders shaping the future, explore their impactful work, and learn how you can contribute to solving critical healthcare questions.</p>
        <div class="flex flex-col sm:flex-row justify-center items-center gap-4 mt-6">
            <p class="text-sm sm:text-base text-gray-600">Begins at 7:00 AM | Contact: <a href="tel:+254709728200" class="text-[#159ed5] hover:text-[#1386b5] transition duration-300">(+254) 709728200</a> | <a href="mailto:researchcoord@kijabehospital.org" class="text-[#159ed5] hover:text-[#1386b5] transition duration-300">researchcoord@kijabehospital.org</a></p>
            <button id="add-to-calendar" class="bg-[#159ed5] hover:bg-[#1386b5] text-white font-serif font-semibold py-2 px-6 rounded-full transition duration-300 inline-flex items-center shadow-md text-sm hover:shadow-lg animate-button">
                <i class="fas fa-calendar-plus mr-2"></i> Add to Calendar
            </button>
            <div class="relative">
                <button id="share-button" class="bg-[#159ed5] hover:bg-[#1386b5] text-white font-serif font-semibold py-2 px-6 rounded-full transition duration-300 inline-flex items-center shadow-md text-sm hover:shadow-lg animate-button">
                    <i class="fas fa-share-alt mr-2"></i> Share
                </button>
                <div id="share-dropdown" class="share-dropdown flex-col gap-2">
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text=Join us for the 5th Annual Research Day 2025 at Kijabe Hospital! March 21, 2025" target="_blank" class="text-[#159ed5] hover:text-[#1386b5] px-2 py-1"><i class="fab fa-twitter"></i> Twitter</a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="text-[#159ed5] hover:text-[#1386b5] px-2 py-1"><i class="fab fa-facebook"></i> Facebook</a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}&title=5th Annual Research Day 2025" target="_blank" class="text-[#159ed5] hover:text-[#1386b5] px-2 py-1"><i class="fab fa-linkedin"></i> LinkedIn</a>
                    <a href="https://api.whatsapp.com/send?text=Join us for the 5th Annual Research Day 2025 at Kijabe Hospital! March 21, 2025 - {{ urlencode(url()->current()) }}" target="_blank" class="text-[#159ed5] hover:text-[#1386b5] px-2 py-1"><i class="fab fa-whatsapp"></i> WhatsApp</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 2025 Program Section -->
<div class="container mx-auto mt-12 mb-14 px-4 sm:px-6 lg:px-8 bg-gray-50 py-10">
    <h2 class="text-3xl sm:text-4xl md:text-5xl font-serif font-bold text-center mb-8 text-[#159ed5] animate-header">Research Day 2025 Program</h2>
    <p class="text-base sm:text-lg font-serif text-center mb-10 animate-fade-in max-w-xl mx-auto text-justify">Experience a day of inspiration on March 21, 2025. Arrive on time for raffle prizes and stay for awards recognizing the best posters.</p>
    <div class="bg-white p-4 sm:p-6 rounded-lg shadow-md animate-fade-in">
        <div class="overflow-x-auto">
            <table class="w-full table-auto border-collapse text-gray-800 font-serif text-sm sm:text-base">
                <thead class="bg-gradient-to-r from-[#159ed5] to-[#1386b5] text-white">
                    <tr>
                        <th class="px-3 sm:px-4 py-3 text-left font-semibold rounded-tl-lg whitespace-nowrap">Time</th>
                        <th class="px-3 sm:px-4 py-3 text-left font-semibold">Event</th>
                        <th class="px-3 sm:px-4 py-3 text-left font-semibold rounded-tr-lg whitespace-nowrap">Facilitator</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b hover:bg-gray-50 transition duration-300">
                        <td class="px-3 sm:px-4 py-3 font-medium whitespace-nowrap">7:00 - 7:05 AM</td>
                        <td class="px-3 sm:px-4 py-3">Prayer and Opening Ceremony</td>
                        <td class="px-3 sm:px-4 py-3">Dr. Arianna Shirk</td>
                    </tr>
                    <tr class="border-b hover:bg-gray-50 transition duration-300">
                        <td class="px-3 sm:px-4 py-3 font-medium whitespace-nowrap">7:05 - 7:10 AM</td>
                        <td class="px-3 sm:px-4 py-3">Introduction to Kijabe Research Department</td>
                        <td class="px-3 sm:px-4 py-3">Dr. Mary Adam</td>
                    </tr>
                    <tr class="border-b hover:bg-gray-50 transition duration-300">
                        <td class="px-3 sm:px-4 py-3 font-medium whitespace-nowrap">7:10 - 7:15 AM</td>
                        <td class="px-3 sm:px-4 py-3">Snooze You Lose Raffle Award</td>
                        <td class="px-3 sm:px-4 py-3">-</td>
                    </tr>
                    <tr class="border-b hover:bg-gray-50 transition duration-300">
                        <td class="px-3 sm:px-4 py-3 font-medium whitespace-nowrap">7:15 - 8:00 AM</td>
                        <td class="px-3 sm:px-4 py-3">Oral Presentations (3)</td>
                        <td class="px-3 sm:px-4 py-3">-</td>
                    </tr>
                    <tr class="border-b hover:bg-gray-50 transition duration-300">
                        <td class="px-3 sm:px-4 py-3 font-medium whitespace-nowrap">8:00 - 8:30 AM</td>
                        <td class="px-3 sm:px-4 py-3">Celebrity Interviews</td>
                        <td class="px-3 sm:px-4 py-3">Dr. Arianna Shirk</td>
                    </tr>
                    <tr class="border-b hover:bg-gray-50 transition duration-300">
                        <td class="px-3 sm:px-4 py-3 font-medium whitespace-nowrap">8:30 - 10:30 AM</td>
                        <td class="px-3 sm:px-4 py-3">Poster Judging <br><span class="text-xs text-gray-600">[Stand next to your poster: MEB 2nd and 3rd Floor]</span></td>
                        <td class="px-3 sm:px-4 py-3">Invited Guests & Research Celebrities</td>
                    </tr>
                    <tr class="border-b hover:bg-gray-50 transition duration-300">
                        <td class="px-3 sm:px-4 py-3 font-medium whitespace-nowrap">12:30 - 1:30 PM</td>
                        <td class="px-3 sm:px-4 py-3">
                            Lunchtime Thematic Group Discussions:
                            <ul class="list-disc ml-4 mt-1 text-xs sm:text-sm space-y-1">
                                <li><strong>Playing By the Rules: Research Grant Compliance</strong> <br> Facilitators: Dr. Dana Brown, Dr. Miriam Njoki, Alice Osiemo</li>
                                <li><strong>Making Research Collaborations Work</strong> <br> Facilitators: Prof. Sam Kinyanjui, Dr. Majid Twahir, Dr. Cyrus Mugo, Dr. Macharia Chege</li>
                                <li><strong>Nurses: We Do Research</strong> <br> Facilitators: Prof. David Gathara, Dr. Peris Kiarie, Gladys Mutisya</li>
                                <li><strong>Bugs vs. Drugs: Antimicrobial Resistance</strong> <br> Facilitators: Prof. Rodney Adam, David Odada, Dr. Belyse Arakaza</li>
                            </ul>
                        </td>
                        <td class="px-3 sm:px-4 py-3">-</td>
                    </tr>
                    <tr class="border-b hover:bg-gray-50 transition duration-300">
                        <td class="px-3 sm:px-4 py-3 font-medium whitespace-nowrap">1:30 - 1:35 PM</td>
                        <td class="px-3 sm:px-4 py-3">Awarding Best Posters</td>
                        <td class="px-3 sm:px-4 py-3">Invited Guests</td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition duration-300">
                        <td class="px-3 sm:px-4 py-3 font-medium whitespace-nowrap">1:35 - 1:50 PM</td>
                        <td class="px-3 sm:px-4 py-3">Closing Ceremony and Cake Sharing</td>
                        <td class="px-3 sm:px-4 py-3">Dr. Chege Macharia, Dr. Arianna Shirk, Dr. Peris Kiarie</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p class="text-sm text-center mt-4 text-gray-600 font-serif">Posters available for viewing all day.</p>
        <div class="mt-6 text-center">
            <a href="{{ asset('researchdayprogram.pdf') }}" class="bg-[#159ed5] hover:bg-[#1386b5] text-white font-serif font-semibold py-2 px-6 rounded-full transition duration-300 inline-flex items-center shadow-md text-sm hover:shadow-lg animate-button" download>
                <i class="fas fa-download mr-2"></i> Download Program (PDF)
            </a>
        </div>
    </div>
</div>

<!-- 2025 Poster Presentations Section -->
<div class="container mx-auto mt-12 mb-14 px-4 sm:px-6 lg:px-8">
    <h2 class="text-3xl sm:text-4xl md:text-5xl font-serif font-bold text-center mb-8 text-[#159ed5] animate-header">2025 Poster Presentations</h2>
    <p class="text-base sm:text-lg font-serif text-center mb-10 animate-fade-in max-w-xl mx-auto text-justify">Showcasing cutting-edge research and quality improvement across five tracks. Awards will be given for top posters in each category, except published works.</p>
    <div class="bg-white p-4 sm:p-6 rounded-lg shadow-md animate-fade-in font-serif">
        <!-- Recently Published Work -->
        <details class="mb-4">
            <summary class="text-lg sm:text-xl font-semibold cursor-pointer text-[#159ed5] hover:text-[#1386b5] transition duration-300">Recently Published Work</summary>
            <div class="poster-grid mt-4">
                @foreach([
                    ['title' => 'Family Health Clinical Officers: Key Professionals to Strengthen Primary Healthcare in Kenya and Beyond', 'authors' => 'Katy Linley, Irene Muthoni, Emmanuel Ndeto, Sarah Kamau, Stella Wangui, Pete'],
                    ['title' => 'A Teledermatology Survey in Kenya', 'authors' => 'Mary B. Adam, MD, MA, PhD; Hashim Kaderbhai, MBBS; Jennifer L. Adams, MD; Rodney D. Adam, MD; Jennifer Fernandez, MD']
                ] as $poster)
                    <div class="poster-card">
                        <h4>{{ $poster['title'] }}</h4>
                        <p class="authors">Authors: {{ $poster['authors'] }}</p>
                        @if($registeredPosters->contains('title', $poster['title']))
                            <a href="{{ route('view-poster', ['id' => $registeredPosters->firstWhere('title', $poster['title'])->id]) }}" target="_blank" class="view-btn inline-block mt-2">View Poster</a>
                        @endif
                    </div>
                @endforeach
            </div>
        </details>

        <!-- Completed Research Projects -->
        <details class="mb-4">
            <summary class="text-lg sm:text-xl font-semibold cursor-pointer text-[#159ed5] hover:text-[#1386b5] transition duration-300">Completed Research Projects</summary>
            <div class="poster-grid mt-4">
                @foreach([
                    ['title' => 'Exploring Caregiver Experiences in Providing Continence Care for Older Adults at AIC Kijabe Hospital\'s Ambulatory Care: A Qualitative Study', 'authors' => 'Stella Kibet, Faith Lelei, Jonathan Nthusi'],
                    ['title' => 'Clinician Perspectives on the Use of Templates to Enhance Documentation Treatment Notes for Infectious Disease Patients: A Qualitative Study', 'authors' => 'Peter Watson, Agwings Chagwira, George Rotich, Naomi Makobu'],
                    ['title' => 'Factors Influencing Adherence to HIV Post-Exposure Prophylaxis Among Healthcare Workers at AIC Kijabe Hospital', 'authors' => 'Dr. Boaz Odhiambo Omenda, Dr. Pete Halestrap, Dr. Patrick Asaava'],
                    ['title' => 'Long-term Survival and Quality of Life Using Self-administered Modified Oral-to-Gastric Tube Feeding: A Case Study from Rural Kenya', 'authors' => 'Hellen Kihoro, Argwings Chagwira, Merecia Nguka'],
                    ['title' => 'Assessment of the Causes and Outcomes of Hospitalization in Children with Sickle Cell Disease at Jaramogi Oginga Odinga Teaching and Referral Hospital', 'authors' => 'O\'Neil Wamukota Kosasia, Ricky Felix Kwayi, Cynthia Moraa Morema, Jackline Wanjiku Githinji'],
                    ['title' => 'Colon Adenocarcinoma Invading the Duodenum, Pancreas, and Superior Mesenteric Vein: A Case Series and Review of the Literature', 'authors' => 'Faith Cheshari, Ivan Marando, Leahcaren N. Oundoh, Dieudonne Afidu Lemfuka, Richard Davis, Joseph Nderitu'],
                    ['title' => 'Diagnoses and Outcomes of Pediatric Admissions from AIC Kijabe Hospital Casualty', 'authors' => 'Arianna Shirk, Caleb Karanja, Emily Hartford, Amelie von-Saint Andre von Armin'],
                    ['title' => 'A Retrospective Analysis of the Outcomes of Cholecystectomies Done at AIC Kijabe Hospital', 'authors' => 'Vallery Logedi, Jack Barasa'],
                    ['title' => 'Laparoscopic Assisted Hemicolectomy at AIC Kijabe Hospital', 'authors' => 'Leahcaren N. Oundoh, Katlyn G. McKay, Afidu Lemfuka, Lydia Kersh, Maya V. Roytman, Caleb Van Essen, Joseph Nderitu, Richard Davis'],
                    ['title' => 'Treatment Outcomes of Wilms Tumor: A Faith-Based Institution\'s Experience', 'authors' => 'Michelle Obat, Ken Muma, Sarah Muma, Mehret Enaro, Muse Freneh'],
                    ['title' => 'One-Year Mortality Outcome for Heart Failure Patients in a Faith-Based Hospital in Kenya', 'authors' => 'Kimani Brian, Mwangi C., Mwende M., Gachau F., Wakuthii Carol, Otieno George'],
                    ['title' => 'Paediatric Ambulatory Surgery in a Rural Hospital: A Contextualized Approach', 'authors' => 'Bethleen Waisiko (MBChB, BSc, FCS-ECSA), David Limo (BSc, FIA), Muma Nyagetuba (MMed, FCS ECSA, MA Bioethics, MBA)'],
                    ['title' => 'Assessment of Phlebotomy Practice and Turnaround Time in Blood Sample Collection: A Descriptive Study', 'authors' => 'Argwings Chagwira, Dr. Laura Timina, Shadrack Yego, George Rotich, Dr. Peter Watson'],
                    ['title' => 'Predicting Mortality in Hypoxemic Adults in Sub-Saharan Africa: Investigating the Prognostic Performance and Utility of Three Severity of Illness Scores', 'authors' => 'Nelly Kebeney, MSc; Tiara Calhoun, MD; George Otieno, MBChB, MMed; Elisabeth Riviello, MD, MPH'],
                    ['title' => 'Prevalence of Musculoskeletal Pain Among Surgeons in Sub-Saharan Africa', 'authors' => 'Vallery Logedi, Richard Davis, Adrian Park'],
                    ['title' => 'Understanding Adolescent Transitions for Youth with Spina Bifida in a LMIC: Using Focused Group Discussions to Evaluate Successes and Challenge Areas Identified By Young Adults Living With Spina Bifida', 'authors' => 'Mercy Mwangi, BSc Community Resource Management; Luke McAuley, MOT, OTR/L; Mary B. Adam, MD']
                ] as $poster)
                    <div class="poster-card">
                        <h4>{{ $poster['title'] }}</h4>
                        <p class="authors">Authors: {{ $poster['authors'] }}</p>
                        @if($registeredPosters->contains('title', $poster['title']))
                            <a href="{{ route('view-poster', ['id' => $registeredPosters->firstWhere('title', $poster['title'])->id]) }}" target="_blank" class="view-btn inline-block mt-2">View Poster</a>
                        @endif
                    </div>
                @endforeach
            </div>
        </details>

        <!-- Quality Improvement Projects -->
        <details class="mb-4">
            <summary class="text-lg sm:text-xl font-semibold cursor-pointer text-[#159ed5] hover:text-[#1386b5] transition duration-300">Quality Improvement Projects</summary>
            <div class="poster-grid mt-4">
                @foreach([
                    ['title' => 'How Relationships Make Quality Improvement Projects Work and Sustainable', 'authors' => 'Wambui Makobu, Angie Donelson, Tod Newman, Mary Adam'],
                    ['title' => 'Mind the Gap: Hand Hygiene Compliance Rates Among Healthcare Workers: An Observational Study & Improvement Opportunity', 'authors' => 'Argwings Chagwira, Grace Njoroge, Dr. Peter Watson, Philip Musiya'],
                    ['title' => 'AIC Kijabe Hospital Community Health Program: Troubleshooting Implementation of eCHIS Kenya', 'authors' => 'Ogweno Brian, Simon Mbugua, Wambui Makobu, Wilson K. Kamiru, Brenda Chepkemoi'],
                    ['title' => 'Assessing the Effectiveness of Mobile Obstetrics Simulation Training in a Low Resource Setting: A Pre-Test and Post-Test Study', 'authors' => 'Phyllis Ngure, Faith Mungai, Immanuel Mugambi, Mary Mungai'],
                    ['title' => 'Complete Hospital Supply Chain Automation: Case of AIC Kijabe Hospital', 'authors' => 'Timothy Makori, Patrick Wairire, Isaac Kenge, Oliver Mulwa, Collins Muigai'],
                    ['title' => 'Evaluating the Efficacy of a Revised Uterotonic Protocol on Maternal Outcomes During Cesarean Delivery at AIC Kijabe Hospital, Kenya: A PICO-Based Quality Improvement Study', 'authors' => 'Christelle Gbiatene Kasoki, MD; Matthew Kynes, MD'],
                    ['title' => 'Faith + Medicine Summit', 'authors' => 'Faith Lelei, Geofrey Ndivo, Joshua Owino, Matt Kynes, Ansley Kynes, Muthoni Magayu, Mfanelo Sobekwa, Mary B. Adam'],
                    ['title' => 'Multimodal Interventions to Improve Hand Hygiene Adherence Among Healthcare Workers in a Resource-Limited Tertiary Teaching Hospital', 'authors' => 'Dr. Watson Maina, Tabby M., Chagwira A., Dr. Belyse Arakaza, Terry M., G. Rotich, Dr. Lepore, G. Kuria, J. Mark, Brenda K., Faith K., Jemutai S.'],
                    ['title' => 'Improving Compliance of Grant Funding Requirements at AIC Kijabe Hospital', 'authors' => 'Alice Osiemo, MPH; Mary Adam, MD, PhD; Angela Donelson, PhD'],
                    ['title' => 'Improving Diabetes Care with a Patient-Held Handbook', 'authors' => 'Katy Linley, Felix Magego, Beth Wanjiku, Irene Muthoni, Alice Wangu, Sarah Kamau, Stella Wangui, Stella K. Musundi, Nancy Njoroge'],
                    ['title' => 'Improving the Quality of Documentation for Diabetes Patients at a Chronic Care Center in a Faith-Based Tertiary Hospital in Kenya', 'authors' => 'Elijah Ntomariu, Kelvin Gathima, Stella Mbugua, Albright M., Dr. Maria O., Tabitha M., Zablon W., Irene M., Bartholomew M., Belinda Jepkogei, Dr. Belyse Arakaza'],
                    ['title' => 'Increase Uptake of Cervical Cancer Screening in Kijabe', 'authors' => 'Dr. Kaya Belknap, Antony Kuria, Paul Ocharo, Beatrice Odera, Sarah Kamau, Dr. Stella Njenga, Carolyne Muthoni, Zipporah Migwe, Dr. Mary Kamau, Mary Wanjiru, Rama Muli'],
                    ['title' => 'Moving Diabetes Patients to Diabetes Clinic', 'authors' => 'Stella Wangui, Anastacia Lau, Joram Jomo, Stella Khavugwi, Mercy Wambui, Stella Kibet'],
                    ['title' => 'Evaluating the Effectiveness of the Pre- and Post-Test Model of Learning in TB Training', 'authors' => 'Emmanuel Ndeto, Elijah Mungathia, Harriet Wanjiru - CPD TEAM'],
                    ['title' => 'Integrating Continuous Quality Improvement into Primary Health Care', 'authors' => 'Emmanuel Ndeto'],
                    ['title' => 'Improving Effective Prenatal Care at the Maternal and Child Health Clinic of AIC Kijabe Hospital', 'authors' => 'Dr. Morgan De Kleine, DNP, CNM, FNP-BC; Dr. Sharanna Johnson, DNP, APRN, FNP-BC, CNE; Dr. Kaya Belknap, MD, MPH'],
                    ['title' => 'Laravel & DevOps: Revolutionizing Clinical Applications at Kijabe Hospital', 'authors' => 'Timothy Makori, Kevin Mwaura, Oliver Mulwa, Simon Ngaruiya, Collins Muigai, Francis Gachau, Godwin Wechuli, Amos Cheruiyot, Augustine Okoth'],
                    ['title' => 'Telemedicine Attempts through Inhouse Development: Feats & Challenges', 'authors' => 'Timothy Makori, Oliver Mulwa, Simon Ngaruiya, Collins Muigai, Kevin Gaitho, Francis Gachau, Emmanuel Njoroge'],
                    ['title' => 'Multimodal Approach to Reducing Blood Culture Contamination at Kijabe Hospital', 'authors' => 'Argwings Chagwira, Dorcas J. Sumukwo, Brenda Kinya'],
                    ['title' => 'Barriers and Facilitators to Hand Hygiene Compliance Among Healthcare Workers in Kijabe Hospital: A Qualitative Study', 'authors' => 'Terry M., Chagwira A., Wambui M., Tabby M., Dr. Belyse A., G. Rotich, Dr. Lepore, Faith K., Brenda K., Dorcas J., Grace N.']
                ] as $poster)
                    <div class="poster-card">
                        <h4>{{ $poster['title'] }}</h4>
                        <p class="authors">Authors: {{ $poster['authors'] }}</p>
                        @if($registeredPosters->contains('title', $poster['title']))
                            <a href="{{ route('view-poster', ['id' => $registeredPosters->firstWhere('title', $poster['title'])->id]) }}" target="_blank" class="view-btn inline-block mt-2">View Poster</a>
                        @endif
                    </div>
                @endforeach
            </div>
        </details>

        <!-- Medical Education and Training Programs -->
        <details class="mb-4">
            <summary class="text-lg sm:text-xl font-semibold cursor-pointer text-[#159ed5] hover:text-[#1386b5] transition duration-300">Medical Education and Training Programs</summary>
            <div class="poster-grid mt-4">
                @foreach([
                    ['title' => 'Integrating Virtual Reality Simulation for Point-of-Care Ultrasound (POCUS) Training in Sub-Saharan Africa', 'authors' => 'Kimani Brian, Arango Susana, Buyck David, Ngure Phyllis, Mugambi Immanuel, Thairu Benjamin, Mwende Mercy, Otieno George'],
                    ['title' => 'NCD Course Development', 'authors' => 'Dr. Kaya Belknap, Dr. Katy Linley, Harriet Wanjiru, Irene Muthoni, Emmanuel Ndeto, Elijah Mungathia'],
                    ['title' => 'What is the Perceived Acceptability, Accuracy and Appropriateness of Online Artificial Intelligence Derived Medical Educational Cases?', 'authors' => 'Artificial Intelligence, Dr. Arianna Shirk, Elijah Ntomariu, Amos Cheruiyot, Dr. Dan Claud, Dr. Peter Halestrap']
                ] as $poster)
                    <div class="poster-card">
                        <h4>{{ $poster['title'] }}</h4>
                        <p class="authors">Authors: {{ $poster['authors'] }}</p>
                        @if($registeredPosters->contains('title', $poster['title']))
                            <a href="{{ route('view-poster', ['id' => $registeredPosters->firstWhere('title', $poster['title'])->id]) }}" target="_blank" class="view-btn inline-block mt-2">View Poster</a>
                        @endif
                    </div>
                @endforeach
            </div>
        </details>

        <!-- Work in Progress -->
        <details class="mb-4">
            <summary class="text-lg sm:text-xl font-semibold cursor-pointer text-[#159ed5] hover:text-[#1386b5] transition duration-300">Work in Progress</summary>
            <div class="poster-grid mt-4">
                @foreach([
                    ['title' => 'Building Respiratory Support in East Africa Through High Flow versus Standard Flow Oxygen Evaluation (BREATHE)', 'authors' => 'Dr. George O. Otieno, Nelly C. Kebeney, Valentine Rambula, Joseph G. Wainaina, James M. Mwinzi, Truphosa O. Awuor, Nelius Wairimu, Francis K. Irangi, Elisabeth Riviello'],
                    ['title' => 'Cerebral Palsy Knowledge by Caregivers with Children with Cerebral Palsy', 'authors' => 'Denis K. Koech, OT, POTS; Luke McAuley, MOTR/L; Jessica Tsotsoros, PhD, OTR/L; Jessica Koster, MOTR/L'],
                    ['title' => 'Communication Challenges Experienced by Caregivers of Children with Autism Spectrum Disorder (ASD) Who are Non-Verbal', 'authors' => 'Shalline Otuma, OT, POTS; Luke McAuley, MOTR/L; Jessica Tsotsoros, PhD, OTR/L; Jessica Koster, MOTR/L'],
                    ['title' => 'Comparison of Newly Designed Wheelchairs Locally Available in Kenya', 'authors' => 'Luke McAuley, MOTR/L; Alpine Kipsang, POT; Ann Chesoli, POT; Edmon Kipruto, POT; Faith Ndungu, POT; Robert Mutuko, POT; Cynthia Chelangat, POTs; Denis Koech, POTs; Lazarus Maraka, POTs; Mary Njoroge, POTs; Maximillah Ikaa, POTs; Shalline Otuma, POTs; Jessica Tsotsoros, PhD, OTR/L'],
                    ['title' => 'Competency in Recognition of Cardiac Arrhythmia in Kenya (CIRCAK)', 'authors' => 'Kimani Brian, Gitura Hannah, Thairu Benjamin, Mwende Mercy, Otieno George, Keller Michael, Lee Burton'],
                    ['title' => 'Evaluating the Process While Testing the Intervention: A Process Evaluation Protocol for the BREATHE Oxygen Therapy Randomized Controlled Trial in Kenya, Malawi, and Rwanda', 'authors' => 'Tiara Calhoun, Lauren A. Onofrey, Felix Limbani, Valentine E. Rambula, Mary B. Adam, Ndaziona Peter Kwanjo Banda, Gahungu Blaise, Gashame Dona Fabiola, MD; Stephen Gordon, Nancy Haff, Esther Kageche, Nelly Kebeney, Julie Lauffenburger, Peter Oduor, George Otieno, Innocent Sulani, Theogene Twagirumugabe, Uwamahoro Doris Lorette, Elisabeth Riviello, Jeanine Condo'],
                    ['title' => 'Impact of Hydrotherapy on Cerebral Palsy Management', 'authors' => 'Mary Njoroge, OT, POTs; Luke McAuley, MOTR/L; Jessica Tsotsoros, PhD, OTR/L; Jessica Koster, MOTR/L'],
                    ['title' => 'Implementation of Evidence-Based Practice by Occupational Therapists in Kenya', 'authors' => 'Maximillah Ikaa, OT, POTS; Luke McAuley, MOTR/L; Jessica Tsotsoros, PhD, OTR/L'],
                    ['title' => 'Patterns of Pediatric Heart Diseases Diagnosed by Echocardiography in a Rural Hospital in Kenya', 'authors' => 'Kimani Brian, Mwende Mercy, Musau Jack, Lokocheria Frank, Otieno George, Arianna Shirk, Jowi Christine'],
                    ['title' => 'Psychological Distress and Coping Strategies Among Caregivers of Children with Autism Spectrum Disorders (ASD)', 'authors' => 'Cynthia Chelangat, OT, POTS; Luke McAuley, MOTR/L; Jessica Tsotsoros, PhD, OTR/L; Jessica Koster, MOTR/L'],
                    ['title' => 'Breaking the Chain: Integrated Active Surveillance and Intervention for Nosocomial Infection Reduction', 'authors' => 'Peter Watson, Agwings Chagwira, George Rotich, Belyse Arakaza, Grace Kuria, Tabitha Muchendu, Christine Murugu, Mary Adam'],
                    ['title' => 'Safety of Administration of Vasopressors Through Peripheral and Central Venous Catheters in a Resource-Limited Rural Kenyan Hospital', 'authors' => 'Brian Kimani, Grace Gao, Linette Lepore, Winslet Okari, Zahra Aghababa, Moses Odhiambo, Alice Mwonge, Jack Musau, Benjamin Thairu, Judy Maneeno, Hannah Wanjiru, Fredrick Ndibaru, Lilian Vihenda, Dorothy Ogoda, Judy Wairimu, Ann Nalungala, Michael Kinuthia, Kinzi Nixon, B. Jason Brotherton, Kristina Rudd'],
                    ['title' => 'Safety of Administration of Vasopressors Through Peripheral and Central Venous Catheters in a Resource-Limited Rural Kenyan Hospital', 'authors' => 'Linette Lepore, Brian Kimani, Grace Gao, Winslet Okari, Zahra Aghababa, Alice Mwonge, Jack Musau, Benjamin Thairu, Judy Maneeno, Hannah Wanjiru, Fredrick Ndibaru, Lilian Vihenda, Ann Nalungala, Michael Kinuthia, Kinzi Nixon, B. Jason Brotherton, Kristina Rudd'],
                    ['title' => 'Impact of Occupational Therapy Interventions on Social Participation and Behavioral Outcomes on Children with Autism Spectrum Disorder in Kenya', 'authors' => 'Lazarus W. Maraka, OT, POTS; Luke McAuley, MOTR/L; Jessica Koster, MOTR/L; Jessica Tsotsoros, PhD, OTR/L'],
                    ['title' => 'Progress Report: Hand Hygiene QI', 'authors' => 'TBD']
                ] as $poster)
                    <div class="poster-card">
                        <h4>{{ $poster['title'] }}</h4>
                        <p class="authors">Authors: {{ $poster['authors'] }}</p>
                        @if($registeredPosters->contains('title', $poster['title']))
                            <a href="{{ route('view-poster', ['id' => $registeredPosters->firstWhere('title', $poster['title'])->id]) }}" target="_blank" class="view-btn inline-block mt-2">View Poster</a>
                        @endif
                    </div>
                @endforeach
            </div>
        </details>
    </div>
</div>

<!-- Distinguished Guests Section -->
<div class="container mx-auto mt-12 mb-14 px-4 sm:px-6 lg:px-8 bg-gray-50 py-10">
    <h2 class="text-3xl sm:text-4xl md:text-5xl font-serif font-bold text-center mb-8 text-[#159ed5] animate-header">Distinguished Guests</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach([
            ['name' => 'Prof. Samson Kinyanjui, PhD', 'title' => 'Head of Training and Capacity Building, KEMRI-Wellcome', 'bio' => 'Director of the Initiative to Develop African Research Leaders (IDEAL). Raised over $82M for capacity building, supporting 150+ PhDs and 260+ Masters. Recipient of the 2019 Chalmers Medal.', 'image' => 'kinyanjui.jpg'],
            ['name' => 'Prof. Rodney D. Adam', 'title' => 'Infectious Disease Specialist', 'bio' => 'Over 35 years of experience in infectious diseases, focusing on hospital-acquired infections and microbiota research. Currently at Aga Khan University Hospital.', 'image' => 'adam.jpg'],
            ['name' => 'Dr. Cyrus Mugo, MBChB, MPH, PhD', 'title' => 'Senior Research Scientist, Kenyatta National Hospital', 'bio' => 'Expert in HIV prevention with over 45 publications. Currently researching social factors in adolescent HIV.', 'image' => 'mugo.jpg'],
            ['name' => 'Dr. Dana Brown, MBChB, MMed, PhD', 'title' => 'Cognitive Scientist', 'bio' => 'Established East Africa\'s first cognition research lab at USIU-Africa. Author of 45+ publications and advocate for open scholarship.', 'image' => 'brown.jpg'],
            ['name' => 'Dr. Majid S. Twahir', 'title' => 'Senior Lecturer, Strathmore University', 'bio' => 'Over 30 years in healthcare and academia. Former CEO of AAR Hospital with extensive publications in medicine and management.', 'image' => 'twahir.jpg'],
            ['name' => 'Dr. Miriam Njoki Karinja', 'title' => 'Senior Program Officer, Science for Africa Foundation', 'bio' => 'Over 10 years in clinical epidemiology, leading the Clinical Trials Community Africa Network (CTCAN).', 'image' => 'karinja.jpg'],
            ['name' => 'Prof. David Gathara', 'title' => 'Intermediate Research Fellow, KEMRI Wellcome Trust', 'bio' => 'Over 13 years in health systems research, focusing on nurse staffing\'s impact on care quality.', 'image' => 'gathara.jpg'],
            ['name' => 'Emmah Ndirangu, MSc Fin, CPA-K', 'title' => 'Finance Manager, Science for Africa Foundation', 'bio' => 'Manages a $180M+ grant portfolio, enhancing financial accountability across Africa.', 'image' => 'ndirangu.jpg']
        ] as $guest)
            <div class="bg-white rounded-lg shadow-md p-4 transform hover:scale-105 transition duration-300 font-serif animate-card">
                <img src="{{ asset('images/guests/' . $guest['image']) }}" alt="{{ $guest['name'] }}" class="w-full h-40 object-cover mb-3 rounded-md guest-image">
                <h3 class="text-lg sm:text-xl font-bold text-[#159ed5]">{{ $guest['name'] }}</h3>
                <p class="text-sm text-gray-600 mb-2">{{ $guest['title'] }}</p>
                <p class="text-gray-700 text-sm text-justify">{{ $guest['bio'] }}</p>
            </div>
        @endforeach
    </div>
</div>

<!-- Published Works Section -->
<div class="container mx-auto mt-12 mb-14 px-4 sm:px-6 lg:px-8">
    <h2 class="text-3xl sm:text-4xl md:text-5xl font-serif font-bold text-center mb-8 text-[#159ed5] animate-header">Published Works (Jan 2024 - Mar 2025)</h2>
    <p class="text-base sm:text-lg font-serif text-center mb-10 animate-fade-in max-w-xl mx-auto text-justify">Recent contributions from Kijabe Hospital researchers to global health knowledge.</p>
    <div class="bg-white p-4 sm:p-6 rounded-lg shadow-md animate-fade-in font-serif">
        <ul class="list-decimal list-inside space-y-3 text-gray-800 text-sm sm:text-base">
            @foreach([
                ['title' => 'Moral Injury in Healthcare: A Low-and-Middle-Income Perspective', 'authors' => 'Anito, M. F., Desalegn, M., Nowotny, N. M., & Hansen, E. N.', 'journal' => 'World Journal of Surgery', 'doi' => '10.1002/wjs.12148'],
                ['title' => 'Swyer-James-MacLeod Syndrome in an East African Patient', 'authors' => 'Berndsen, R. E., Montgomery, M. E., Belknap, K., & Berry-Caban, C. S.', 'journal' => 'Cureus', 'doi' => '10.7759/cureus.69874'],
                ['title' => 'Development and Delivery of a Higher Diploma in Emergency Medicine and Critical Care for Clinical Officers in Kenya', 'authors' => 'Halestrap, P., Aliba, D., Otieno, G., Brotherton, B. J., Gitura, H. W., Matson, J. E., Lee, B. W., & Mbugua, E.', 'journal' => 'African Journal of Emergency Medicine', 'doi' => '10.1016/j.afjem.2023.08.006'],
                ['title' => 'Navigating the Challenges of Surgical Research in Low-and-Middle Income Settings', 'authors' => 'Hinson, C., Alkhatib, J., Williams, L., Zope, M., Ameh, E. A., & Nthumba, P.', 'journal' => 'The American Journal of Surgery', 'doi' => '10.1016/j.amjsurg.2025.116230'],
                ['title' => 'Global Surgery Is Stronger When Infection Prevention and Control Is Incorporated', 'authors' => 'Hinson, C., Kilpatrick, C., Rasa, K., Ren, J., Nthumba, P., Sawyer, R., & Ameh, E.', 'journal' => 'BMC Surgery', 'doi' => '10.1186/s12893-024-02695-7'],
                ['title' => 'A Time Out for Prayer', 'authors' => 'Javid, P. J., Joharifard, S., Nyagetuba, M. J. K., & Hansen, E. N.', 'journal' => 'World Journal of Surgery', 'doi' => '10.1002/wjs.12149'],
                ['title' => 'Bidirectional Medical Training: Legislative Advocacy and a Step Towards Equity in Global Health Education', 'authors' => 'Kauffmann, R. M., & Walters, C.', 'journal' => 'Annals of Surgery', 'doi' => '10.1097/SLA.0000000000005964'],
                ['title' => 'Machines Matter Too: Including Biomedical Engineering Partnerships in Global Health Initiatives', 'authors' => 'Kehinde, T., Biwott, J., Sund, G., & Kynes, J. M.', 'journal' => 'The Lancet Global Health', 'doi' => '10.1016/S2214-109X(24)00294-8'],
                ['title' => 'Prevalence, Aetiology, and Hospital Outcomes of Paediatric Acute Critical Illness in Resource-Constrained Settings', 'authors' => 'Kortz, T. B., Holloway, A., Agulnik, A., et al.', 'journal' => 'The Lancet Global Health', 'doi' => '10.1016/S2214-109X(24)00450-9'],
                ['title' => 'Family Health Clinical Officers: Key Professionals to Strengthen Primary Healthcare in Kenya', 'authors' => 'Linley, K.', 'journal' => 'African Journal of Primary Health Care & Family Medicine', 'doi' => '10.4102/phcfm.v16i1.4594'],
                ['title' => 'Barriers to the Use of Patient-Reported Outcome Measures in Low- and Middle-Income Countries', 'authors' => 'Malapati, S. H., Edelen, M. O., Nthumba, P. M., Ranganathan, K., & Pusic, A. L.', 'journal' => 'Plastic and Reconstructive Surgery Global Open', 'doi' => '10.1097/GOX.0000000000005576'],
                ['title' => 'Systemic Emergency Department Performance in a Low Resource Tertiary Health Facility in Central Kenya', 'authors' => 'Milma, M., & Marsuk, E.', 'journal' => 'African Journal of Emergency Medicine', 'doi' => '10.1016/j.afjem.2023.05.008'],
                ['title' => 'Patient Satisfaction With, and Outcomes of, Ultrasound-Guided Regional Anesthesia at a Referral Hospital in Tanzania', 'authors' => 'Mohamed, S. S., Temu, R., Komba, L. F., et al.', 'journal' => 'Anesthesia and Analgesia', 'doi' => '10.1213/ANE.000000000000574'],
                ['title' => 'Implementation of a Formalized Evaluation and Planning Tool to Improve Pediatric Oncology Outcomes in Kenya', 'authors' => 'Mutua, D., Omotola, A., Bonilla, M., et al.', 'journal' => 'Pediatric Blood & Cancer', 'doi' => '10.1002/pbc.30657'],
                ['title' => 'Using Continuous Quality Improvement in Community-Based Programming During Disasters: Lessons Learned from the 2015 Ebola Crisis', 'authors' => 'Nally, C., Temmerman, M., Van De Voorde, P., Koroma, A., & Adam, M.', 'journal' => 'Disaster Medicine and Public Health Preparedness', 'doi' => '10.1017/dmp.2024.270'],
                ['title' => 'Global Surgery: The Challenges and Strategies to Win a War That Must Be Won', 'authors' => 'Nthumba, P. M.', 'journal' => 'Plastic and Reconstructive Surgery Global Open', 'doi' => '10.1097/GOX.0000000000005953'],
                ['title' => 'Patient Safety in a Rural Sub-Saharan Africa Hospital: A 7-Year Experience at AIC Kijabe Hospital', 'authors' => 'Nthumba, P. M., Mwangi, C., & Odhiambo, M.', 'journal' => 'PLOS Global Public Health', 'doi' => '10.1371/journal.pgph.0003919'],
                ['title' => 'The State of Surgical Research in Sub-Saharan Africa: An Urgent Call for Surgical Research Trainers', 'authors' => 'Nthumba, P. M., Odhiambo, M., Pusic, A., et al.', 'journal' => 'Plastic and Reconstructive Surgery Global Open'],
                ['title' => 'Glomus Tumors: A Systematic Review of the Sub-Saharan Africa Experience', 'authors' => 'Nthumba, P. M., & Oundoh, L. N.', 'journal' => 'Plastic and Reconstructive Surgery Global Open', 'doi' => '10.1097/GOX.00000000000059564'],
                ['title' => 'Building a Sustainable Free Flap Program in a Resource-Limited Setting: A 12-Year Humanitarian Effort', 'authors' => 'Prasad, K., Peterson, N., Nolen, D., et al.', 'journal' => 'Head & Neck', 'doi' => '10.1002/hed.27640'],
                ['title' => 'The Maintenance and Interface of Two Wheelchairs Used by Children with Cerebral Palsy in Kenya', 'authors' => 'Tsotsoros, J., Chamberlin, H., Collins, R., McDonald, K., & McAuley, L.', 'journal' => 'Disability and Rehabilitation Assistive Technology', 'doi' => '10.1080/17483107.2024.2374047'],
                ['title' => 'Safe Anesthesia Care in Western Kenya: A Preliminary Assessment of Nurse Anesthetists', 'authors' => 'Umutesi, G., McEvoy, M. D., Starnes, J. R., et al.', 'journal' => 'Anesthesia and Analgesia', 'doi' => '10.1213/ANE.0000000000004266'],
                ['title' => 'Perioperative Outcomes at Three Rural Rwandan District Hospitals: A 28-Day Cohort Study', 'authors' => 'Umutesi, G., Mizero, J., Riviello, R., et al.', 'journal' => 'BMJ Global Health', 'doi' => '10.1136/bmjgh-2024-017354'],
                ['title' => 'UPESI: Swahili Translation of the FAST Acronym for Stroke Awareness Campaigns in East Africa', 'authors' => 'Waweru, P. K., Yulu, E., Matuja, S. S., & Gatimu, S. M.', 'journal' => 'African Journal of Emergency Medicine', 'doi' => '10.1016/j.afjem.2024.05.003']
            ] as $pub)
                <li>
                    <strong>{{ $pub['title'] }}</strong> <br>
                    Authors: {{ $pub['authors'] }} <br>
                    <em>{{ $pub['journal'] }}</em> <br>
                    @isset($pub['doi'])
                        @if($pub['doi'] !== '')
                            <a href="https://doi.org/{{ $pub['doi'] }}" class="text-[#159ed5] hover:text-[#1386b5] transition duration-300" target="_blank">DOI: {{ $pub['doi'] }}</a>
                        @endif
                    @endisset
                </li>
            @endforeach
        </ul>
    </div>
</div>

<!-- Scripts -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Slider
        const slides = document.querySelectorAll('#slider > div');
        let current = 0;
        setInterval(() => {
            slides[current].classList.remove('opacity-100');
            slides[current].classList.add('opacity-0');
            current = (current + 1) % slides.length;
            slides[current].classList.remove('opacity-0');
            slides[current].classList.add('opacity-100');
        }, 5000);

        // Countdown and Program Display
        const countdownElement = document.getElementById('countdown');
        const eventDate = new Date('2025-03-21T07:00:00+03:00'); // Kenya time (UTC+3)
        const program = [
            { start: '07:00', end: '07:05', event: 'Prayer and Opening Ceremony', facilitator: 'Dr. Arianna Shirk' },
            { start: '07:05', end: '07:10', event: 'Introduction to Kijabe Research Department', facilitator: 'Dr. Mary Adam' },
            { start: '07:10', end: '07:15', event: 'Snooze You Lose Raffle Award', facilitator: '-' },
            { start: '07:15', end: '08:00', event: 'Oral Presentations (3)', facilitator: '-' },
            { start: '08:00', end: '08:30', event: 'Celebrity Interviews', facilitator: 'Dr. Arianna Shirk' },
            { start: '08:30', end: '10:30', event: 'Poster Judging', facilitator: 'Invited Guests & Research Celebrities' },
            { start: '12:30', end: '13:30', event: 'Lunchtime Thematic Group Discussions', facilitator: '-' },
            { start: '13:30', end: '13:35', event: 'Awarding Best Posters', facilitator: 'Invited Guests' },
            { start: '13:35', end: '13:50', event: 'Closing Ceremony and Cake Sharing', facilitator: 'Dr. Chege Macharia, Dr. Arianna Shirk, Dr. Peris Kiarie' }
        ];

        function updateCountdown() {
            const now = new Date();
            const timeLeft = eventDate - now;

            if (timeLeft > 0) {
                const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
                const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
                countdownElement.innerHTML = `
                    <h3>Countdown to Research Day</h3>
                    <div class="countdown-values">
                        <span>${days}d</span>
                        <span>${hours}h</span>
                        <span>${minutes}m</span>
                        <span>${seconds}s</span>
                    </div>
                `;
            } else if (now >= eventDate && now <= new Date('2025-03-21T13:50:00+03:00')) {
                const currentTime = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
                const currentEvent = program.find(p => currentTime >= p.start && currentTime < p.end) || { event: 'Event in Progress', facilitator: '' };
                countdownElement.innerHTML = `
                    <h3>Now Happening</h3>
                    <p>${currentEvent.event}</p>
                    <p>${currentEvent.facilitator !== '-' ? 'Facilitator: ' + currentEvent.facilitator : ''}</p>
                `;
            } else {
                countdownElement.innerHTML = `
                    <h3>Research Day 2025</h3>
                    <p>Event Concluded</p>
                `;
            }
        }
        updateCountdown();
        setInterval(updateCountdown, 1000);

        // ICS Generation
        document.getElementById('add-to-calendar').addEventListener('click', () => {
            const event = {
                start: '20250321T070000',
                end: '20250321T135000',
                title: '5th Annual Research Day 2025',
                description: 'Celebrating Research Excellence at Kijabe Hospital. Contact: (+254) 709728200, researchcoord@kijabehospital.org',
                location: 'Theater Conference Hall, Kijabe Hospital, Kijabe, Kenya'
            };
            const ics = [
                'BEGIN:VCALENDAR',
                'VERSION:2.0',
                'BEGIN:VEVENT',
                `DTSTART:${event.start}`,
                `DTEND:${event.end}`,
                `SUMMARY:${event.title}`,
                `DESCRIPTION:${event.description}`,
                `LOCATION:${event.location}`,
                'END:VEVENT',
                'END:VCALENDAR'
            ].join('\r\n');
            const blob = new Blob([ics], { type: 'text/calendar' });
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = 'research-day-2025.ics';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(url);
        });

        // Share Dropdown Toggle
        const shareButton = document.getElementById('share-button');
        const shareDropdown = document.getElementById('share-dropdown');
        shareButton.addEventListener('click', () => {
            shareDropdown.classList.toggle('active');
        });
        document.addEventListener('click', (e) => {
            if (!shareButton.contains(e.target) && !shareDropdown.contains(e.target)) {
                shareDropdown.classList.remove('active');
            }
        });

        // Staggered animation for guest cards
        const cards = document.querySelectorAll('.animate-card');
        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });

        // Staggered animation for poster cards
        const posterCards = document.querySelectorAll('.poster-card');
        posterCards.forEach((card, index) => {
            card.style.animation = `popIn 0.6s ease-out forwards`;
            card.style.animationDelay = `${index * 0.05}s`;
        });
    });
</script>

@endsection
