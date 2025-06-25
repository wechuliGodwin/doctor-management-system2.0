<!-- resources/views/layouts/footer.blade.php -->
<footer class="bg-[#159ed5] text-white py-10 mt-auto w-full relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-8">
            <!-- About Us Section -->
            <div>
                <h3 class="text-lg font-semibold mb-4">About Us</h3>
                <p class="text-sm">
                    We exist to glorify God through compassionate health care provision, training, and spiritual ministry in Jesus Christ.
                </p>
                <div class="flex space-x-4 mt-4">
                    <a href="https://www.facebook.com/kijabehospital" target="_blank" class="text-white hover:text-gray-200">
                        <i class="bi bi-facebook text-xl"></i>
                    </a>
                    <a href="https://x.com/kijabehospital" target="_blank" class="text-white hover:text-gray-200">
                        <i class="bi bi-twitter text-xl"></i>
                    </a>
                    <a href="https://www.linkedin.com/company/AICKijabeHospital" target="_blank" class="text-white hover:text-gray-200">
                        <i class="bi bi-linkedin text-xl"></i>
                    </a>
                    <a href="https://api.whatsapp.com/send?phone=254709728200" target="_blank" class="text-white hover:text-gray-200">
                        <i class="bi bi-whatsapp text-xl"></i>
                    </a>
                </div>
            </div>
            <!-- Health Services Section -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Health Services</h3>
                <ul class="text-sm space-y-2">
                    <li><a href="{{ route('outpatient') }}" class="hover:underline">Outpatient Services</a></li>
                    <li><a href="{{ route('icu') }}" class="hover:underline">Inpatient Services</a></li>
                    <li><a href="{{ route('daycase-surgeries') }}" class="hover:underline">Theater Services</a></li>
                    <li><a href="{{ route('emergency-surgeries') }}" class="hover:underline">Emergency Surgery</a></li>
                    <li><a href="{{ route('radiology') }}" class="hover:underline">Diagnostics</a></li>
                </ul>
            </div>
            <!-- Helpful Links Section -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Helpful Links</h3>
                <ul class="text-sm space-y-2">
                    <li><a href="#" class="hover:underline">Telemedicine</a></li>
                    <li><a href="{{ route('newsletters') }}" class="hover:underline">Newsletters</a></li>
                    <li><a href="{{ route('notices') }}" class="hover:underline">Notices & Others</a></li>
                    <li><a href="{{ route('guidelines') }}" class="hover:underline">Guidelines</a></li>
                    <li><a href="https://recruit.kijabehospital.org/" class="hover:underline">Careers Portal</a></li>
                </ul>
            </div>
            <!-- Other Links Section -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Other Links</h3>
                <ul class="text-sm space-y-2">
                    <li><a href="{{ route('guidelines') }}" class="hover:underline">Patient Resources</a></li>
                    <li><a href="{{ route('blog') }}" class="hover:underline">News & Updates (Blog)</a></li>
                    <li><a href="{{ route('brochure-download') }}" class="hover:underline">Downloads</a></li>
                    <li><a href="#" class="hover:underline">Privacy Notice</a></li>
                    <li><a href="https://recruit.kijabehospital.org" target="_blank" class="hover:underline">Careers</a></li>
                    <li><a href="{{ route('sitemap') }}" class="hover:underline">Sitemap</a></li>
                </ul>
            </div>
        </div>
        
        <!-- Sticky Chatbot/Email Button -->
        <div class="fixed bottom-4 right-4 z-40">
            <a href="{{ route('contact') }}" id="chatEmailButton" target="_blank" class="bg-[#159ed5] hover:bg-[#4A4A4A] text-white border border-white px-4 py-2 rounded-full flex items-center space-x-2 transition duration-300 shadow-md">
                <i id="chatEmailIcon" class="fa fa-comments text-xl"></i>
                <span id="chatEmailText">Chat</span>
            </a>
        </div>

        <!-- Sticky Feedback Button -->
        <div class="fixed bottom-4 left-4 z-40">
            <a href="{{ route('feedback.create') }}" id="feedbackButton" class="bg-[#159ed5] hover:bg-[#4A4A4A] text-white border border-white px-4 py-2 rounded-full flex items-center space-x-2 transition duration-300 shadow-md">
                <i class="fa fa-comment-dots text-xl"></i>
                <span>Feedback</span>
            </a>
        </div>

        <!-- Cookie Notice -->
        <div id="cookieNotice" class="fixed bottom-0 left-0 w-full bg-gray-900 text-white py-4 px-6 text-center transition-transform duration-500 transform translate-y-full z-50">
            <p class="text-sm">
                We use essential cookies to make our site work. With your consent, we may also use non-essential cookies to improve user experience and analyze website traffic. 
                By clicking "Accept," you agree to our website's cookie use as described in our <a href="#" class="underline">Cookie Policy</a>. 
                You can change your cookie settings at any time by clicking <a href="#" class="underline">Preferences</a>.
                <button id="cookieConsentButton" class="bg-[#159ed5] hover:bg-[#1386b5] text-white font-bold py-1 px-3 rounded ml-2">Accept</button>
            </p>
        </div>

        <div class="text-center mt-8">
            <p class="text-sm">&copy; {{ date('Y') }} AIC Kijabe Hospital. All rights reserved.</p>
        </div>
    </div>
</footer>

<!-- Scripts for Cookie Notice, Button Toggle, and Time-Based Icon Change -->
<script>
    // Function to set a cookie with an expiration time in days
    function setCookie(name, value, days) {
        const d = new Date();
        d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
        let expires = "expires=" + d.toUTCString();
        document.cookie = name + "=" + value + ";" + expires + ";path=/";
    }

    // Function to get a cookie by name
    function getCookie(name) {
        let decodedCookie = decodeURIComponent(document.cookie);
        let ca = decodedCookie.split(';');
        name = name + "=";
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1);
            if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
        }
        return "";
    }

    // Display the cookie notice if consent not given or expired
    window.onload = function() {
        if (!getCookie("cookieConsent")) {
            const cookieNotice = document.getElementById("cookieNotice");
            cookieNotice.classList.remove("translate-y-full");  // Show banner with animation
            
            // Automatically hide the notice after 10 seconds if no interaction
            setTimeout(() => {
                cookieNotice.classList.add("translate-y-full");
            }, 10000);  // 10 seconds
        }

        // Set the chat button icon and link based on time
        setChatEmailButton();
    };

    // Set cookie and hide notice on consent button click
    document.getElementById("cookieConsentButton").onclick = function() {
        setCookie("cookieConsent", "true", 365); // Set consent for 1 year
        document.getElementById("cookieNotice").classList.add("translate-y-full"); // Hide banner
    };

    // Function to set the chat/email button based on time
    function setChatEmailButton() {
        const chatEmailButton = document.getElementById("chatEmailButton");
        const chatEmailIcon = document.getElementById("chatEmailIcon");
        const chatEmailText = document.getElementById("chatEmailText");

        const currentDate = new Date();
        const currentHour = currentDate.getHours();
        const currentDay = currentDate.getDay(); // 0 is Sunday, 6 is Saturday

        // If it's after 5 PM or before 8 AM, or it's the weekend, use email
        if (currentHour < 8 || currentHour >= 17 || currentDay === 0 || currentDay === 6) {
            chatEmailButton.href = "{{ route('contact') }}";
            chatEmailText.innerText = "Email";
            chatEmailIcon.className = "fa fa-envelope text-xl";
        } else {
            chatEmailButton.href = "{{ route('contact') }}";
            chatEmailText.innerText = "Chat";
            chatEmailIcon.className = "fa fa-comments text-xl";
        }
    }
</script>

<style>
    /* Smooth slide-up effect for hiding */
    .translate-y-full {
        transform: translateY(100%);
    }

    /* Slide-in effect for the cookie notice */
    #cookieNotice {
        transition: transform 0.5s ease-in-out;
    }
</style>
