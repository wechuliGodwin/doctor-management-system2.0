<!DOCTYPE html>
<html>
<head>
    <title>Kijabe Telemedicine - Doctor</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1 class="title">Kijabe Telemedicine</h1>
    <p id="notification" hidden></p>
    <div class="user-info">
        <i class="fas fa-clock"></i> <div class="timer" id="timer">00:00:00</div>
    </div>

    <!-- Entry Modal for Meeting -->
    <div class="entry-modal" id="entry-modal">
        <p>Create a Meeting</p>
        <input id="room-input" class="room-input" placeholder="Enter Room ID" value="{{ $appointments->meeting_id ?? '' }}">
        <div class="barton">
            <button onclick="createRoom()" title="Create a new meeting room">Create Meeting</button>
        </div>
    </div>

    <!-- Meet Area -->
    <div class="meet-area">
        <video id="remote-video" autoplay playsinline></video>
        <video id="local-video" autoplay muted playsinline></video>
        <div class="controls">
            <button class="start-recording" onclick="startRecording()" title="Start recording the session">
                <i class="fas fa-video-camera"></i>
            </button>
            <button class="stop-recording" onclick="stopRecording()" title="Stop recording the session">
                <i class="fas fa-stop"></i>
            </button>
            <button class="screen-share" onclick="startScreenShare()" title="Share your screen">
                <i class="fas fa-desktop"></i>
            </button>
            <button class="end-call" onclick="endCall()" title="End the call">
                <i class="fas fa-phone-slash"></i>
            </button>
            <button class="mute-call" id="mute-button" onclick="muteCall()" title="Mute or unmute the microphone">
                <i class="fas fa-microphone" id="mute-icon"></i>
            </button>
        </div>
    </div>

    <script src="https://unpkg.com/peerjs@1.3.1/dist/peerjs.min.js"></script>
    <script src="script.js"></script>

    <script>
        // Timer logic
        let timeElapsed = 0;
        let timerInterval;
        function startTimer() {
            timerInterval = setInterval(() => {
                timeElapsed++;
                const hours = String(Math.floor(timeElapsed / 3600)).padStart(2, '0');
                const minutes = String(Math.floor((timeElapsed % 3600) / 60)).padStart(2, '0');
                const seconds = String(timeElapsed % 60).padStart(2, '0');
                document.getElementById('timer').textContent = `${hours}:${minutes}:${seconds}`;
            }, 1000);
        }

        function updateTimer() {
            document.getElementById('timer').textContent = '00:00:00'; // Reset on load
        }

        // Override endCall to include redirect after cleanup
        const originalEndCall = window.endCall;
        window.endCall = function() {
            originalEndCall(); // Call script.js version for cleanup
            setTimeout(() => {
                window.location.href = "/dashboard"; // Keep as is per original
            }, 500); // Delay to allow cleanup
        };

        // Log appointment data for debugging
        const appointmentId = {{ $appointments->id ?? 'null' }};
        const meetingId = '{{ addslashes($appointments->meeting_id ?? '') }}';
        console.log('Appointment ID:', appointmentId);
        console.log('Meeting ID:', meetingId);
    </script>
</body>
</html>
