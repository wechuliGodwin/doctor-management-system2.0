const PRE = "kijabe";
const SUF = "MEET";
let room_id;
let local_stream;
let screenStream;
let peer = null;
let currentPeer = null;
let screenSharing = false;
let isMuted = false;

// Screen recording variables
let mediaRecorder;
let recordedChunks = [];
let isRecording = false;

document.addEventListener("DOMContentLoaded", () => {
    updateTimer(); // Ensure timer initializes on page load
});

// Utility to handle notifications
function notify(msg, duration = 3000) {
    const notification = document.getElementById("notification");
    notification.innerHTML = msg;
    notification.hidden = false;
    clearTimeout(notification.timeout); // Clear any existing timeout
    notification.timeout = setTimeout(() => {
        notification.hidden = true;
    }, duration);
}

// Create a room (host side)
async function createRoom() {
    console.log("Creating Room");
    const room = document.getElementById("room-input").value.trim();
    if (!room) {
        alert("Please enter a room number");
        return;
    }
    room_id = PRE + room + SUF;
    peer = new Peer(room_id);

    peer.on('open', async (id) => {
        console.log("Peer Connected with ID:", id);
        hideModal();
        try {
            local_stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
            setLocalStream(local_stream);
            startTimer();
            notify("Waiting for peer to join.");
        } catch (err) {
            console.error("Error accessing media devices:", err);
            notify("Failed to access camera/microphone. Please check permissions.");
        }
    });

    peer.on('call', (call) => {
        call.answer(local_stream);
        call.on('stream', (stream) => setRemoteStream(stream));
        call.on('error', (err) => console.error("Call error:", err));
        currentPeer = call;
    });

    peer.on('error', (err) => {
        console.error("Peer error:", err);
        notify("An error occurred with the connection.");
    });
}

// Join a room (patient side)
async function joinRoom() {
    console.log("Joining Room");
    const room = document.getElementById("room-input").value.trim();
    if (!room) {
        alert("Please enter a room number");
        return;
    }
    room_id = PRE + room + SUF;
    hideModal();
    peer = new Peer();

    peer.on('open', async (id) => {
        console.log("Connected with ID:", id);
        try {
            local_stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
            setLocalStream(local_stream);
            notify("Joining peer");
            const call = peer.call(room_id, local_stream);
            call.on('stream', (stream) => setRemoteStream(stream));
            call.on('error', (err) => console.error("Call error:", err));
            currentPeer = call;
        } catch (err) {
            console.error("Error accessing media devices:", err);
            notify("Failed to access camera/microphone. Please check permissions.");
        }
    });

    peer.on('error', (err) => {
        console.error("Peer error:", err);
        notify("An error occurred with the connection.");
    });
}

// Set local video stream
function setLocalStream(stream) {
    const video = document.getElementById("local-video");
    if (video) {
        video.srcObject = stream;
        video.muted = true;
        video.play().catch((err) => console.error("Error playing local video:", err));
    }
}

// Set remote video stream
function setRemoteStream(stream) {
    const video = document.getElementById("remote-video");
    if (video) {
        video.srcObject = stream;
        video.play().catch((err) => console.error("Error playing remote video:", err));
    }
}

// Hide entry modal
function hideModal() {
    const modal = document.getElementById("entry-modal");
    if (modal) modal.hidden = true;
}

// Start recording
async function startRecording() {
    if (isRecording) {
        alert("Recording already in progress.");
        return;
    }

    const combinedStream = new MediaStream();
    if (local_stream) local_stream.getTracks().forEach(track => combinedStream.addTrack(track));
    if (screenSharing && screenStream) screenStream.getTracks().forEach(track => combinedStream.addTrack(track));
    if (currentPeer?.peerConnection?.getRemoteStreams) {
        currentPeer.peerConnection.getRemoteStreams().forEach(remoteStream => {
            remoteStream.getTracks().forEach(track => combinedStream.addTrack(track));
        });
    }

    try {
        const mimeType = MediaRecorder.isTypeSupported('video/mp4') ? 'video/mp4' : 'video/webm';
        mediaRecorder = new MediaRecorder(combinedStream, { mimeType });

        mediaRecorder.ondataavailable = (event) => recordedChunks.push(event.data);
        mediaRecorder.onstop = () => {
            const blob = new Blob(recordedChunks, { type: mimeType });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `Online-session.${mimeType.split('/')[1]}`;
            a.click();
            URL.revokeObjectURL(url); // Clean up
            recordedChunks = [];
        };

        mediaRecorder.onerror = (err) => console.error("Recording error:", err);
        mediaRecorder.start();
        isRecording = true;
        notify("Recording started.");
    } catch (err) {
        console.error("Error starting recording:", err);
        notify("Failed to start recording.");
    }
}

// Stop recording
function stopRecording() {
    if (!isRecording) {
        alert("No recording in progress.");
        return;
    }
    mediaRecorder.stop();
    isRecording = false;
    notify("Recording stopped. Downloading...");
}

// Start screen sharing
async function startScreenShare() {
    if (screenSharing) {
        stopScreenSharing();
        return;
    }

    try {
        screenStream = await navigator.mediaDevices.getDisplayMedia({
            video: { width: { ideal: 1280 }, height: { ideal: 720 }, frameRate: { ideal: 30 } }
        });
        const videoTrack = screenStream.getVideoTracks()[0];
        videoTrack.onended = stopScreenSharing;

        if (peer && currentPeer) {
            const sender = currentPeer.peerConnection.getSenders().find(s => s.track.kind === 'video');
            if (sender) sender.replaceTrack(videoTrack);
            screenSharing = true;
            notify("Screen sharing started.");
        }
    } catch (err) {
        console.error("Error starting screen share:", err);
        notify("Failed to start screen sharing.");
    }
}

// Stop screen sharing
function stopScreenSharing() {
    if (!screenSharing) return;
    const videoTrack = local_stream?.getVideoTracks()[0];
    if (peer && currentPeer && videoTrack) {
        const sender = currentPeer.peerConnection.getSenders().find(s => s.track.kind === 'video');
        if (sender) sender.replaceTrack(videoTrack);
    }

    screenStream?.getTracks().forEach(track => track.stop());
    screenSharing = false;
    notify("Screen sharing stopped.");
}

// End call
function endCall() {
    if (currentPeer) {
        currentPeer.close();
        currentPeer = null;
    }
    if (local_stream) {
        local_stream.getTracks().forEach(track => track.stop());
        local_stream = null;
    }
    if (screenSharing) stopScreenSharing();
    if (isRecording) stopRecording();

    const localVideo = document.getElementById("local-video");
    const remoteVideo = document.getElementById("remote-video");
    if (localVideo) localVideo.srcObject = null;
    if (remoteVideo) remoteVideo.srcObject = null;

    notify("Call ended.");
    console.log("Call has been ended");
}

// Mute/unmute call
function muteCall() {
    if (!local_stream) {
        notify("No active call to mute.");
        return;
    }

    const audioTrack = local_stream.getAudioTracks()[0];
    if (audioTrack) {
        audioTrack.enabled = !audioTrack.enabled;
        isMuted = !audioTrack.enabled;
        const muteIcon = document.getElementById("mute-icon");
        muteIcon?.classList.toggle("fa-microphone", !isMuted);
        muteIcon?.classList.toggle("fa-microphone-slash", isMuted);
        notify(isMuted ? "Microphone muted." : "Microphone unmuted.");
    } else {
        notify("No audio track available.");
    }
}
