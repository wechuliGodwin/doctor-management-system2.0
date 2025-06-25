
@extends('layouts.header')

@section('content')

    <!-- Add entry-modal here -->
    <div class="entry-modal" id="entry-modal">
        <input type="text" id="room-input" class="room-input" placeholder="Meeting ID" value="{{ old('meeting_id', $meeting_id) }}">
        <button onclick="createRoom()">Start meeting</button>
        <button onclick="joinRoom()">Join Room</button>
    </div>

    <video id="local-video" autoplay></video>
    <video id="remote-video" autoplay></video>

    <!-- Add your JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/peerjs@1.3.1"></script>
    <script>
        const PRE = "DELTA";
        const SUF = "MEET";
        var room_id;
        var getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
        var local_stream;
        var screenStream;
        var peer = null;
        var currentPeer = null;
        var screenSharing = false;

        function createRoom() {
            console.log("Creating Room");
            let room = document.getElementById("room-input").value.trim(); // Trim spaces
            if (room === "") {
                alert("Please enter room number");
                return;
            }
            room_id = PRE + room + SUF;
            peer = new Peer(room_id);
            peer.on('open', (id) => {
                console.log("Peer Connected with ID: ", id);
                hideModal();
                getUserMedia({ video: true, audio: true }, (stream) => {
                    local_stream = stream;
                    setLocalStream(local_stream);
                }, (err) => {
                    console.log(err);
                });
                notify("Waiting for peer to join.");
            });
            peer.on('call', (call) => {
                call.answer(local_stream);
                call.on('stream', (stream) => {
                    setRemoteStream(stream);
                });
                currentPeer = call;
            });
        }

        function setLocalStream(stream) {
            let video = document.getElementById("local-video");
            video.srcObject = stream;
            video.muted = true;
            video.play();
        }

        function setRemoteStream(stream) {
            let video = document.getElementById("remote-video");
            video.srcObject = stream;
            video.play();
        }

        function hideModal() {
            document.getElementById("entry-modal").hidden = true; // This will now work
        }

        function notify(msg) {
            let notification = document.getElementById("notification");
            notification.innerHTML = msg;
            notification.hidden = false;
            setTimeout(() => {
                notification.hidden = true;
            }, 3000);
        }

        function joinRoom() {
            console.log("Joining Room");
            let room = document.getElementById("room-input").value.trim(); // Trim spaces
            if (room === "") {
                alert("Please enter room number");
                return;
            }
            room_id = PRE + room + SUF;
            hideModal();
            peer = new Peer();
            peer.on('open', (id) => {
                console.log("Connected with Id: " + id);
                getUserMedia({ video: true, audio: true }, (stream) => {
                    local_stream = stream;
                    setLocalStream(local_stream);
                    notify("Joining peer");
                    let call = peer.call(room_id, stream);
                    call.on('stream', (stream) => {
                        setRemoteStream(stream);
                    });
                    currentPeer = call;
                }, (err) => {
                    console.log(err);
                });
            });
        }

        function startScreenShare() {
            if (screenSharing) {
                stopScreenSharing();
            }
            navigator.mediaDevices.getDisplayMedia({ video: true }).then((stream) => {
                screenStream = stream;
                let videoTrack = screenStream.getVideoTracks()[0];
                videoTrack.onended = () => {
                    stopScreenSharing();
                };
                if (peer) {
                    let sender = currentPeer.peerConnection.getSenders().find(function (s) {
                        return s.track.kind === videoTrack.kind;
                    });
                    sender.replaceTrack(videoTrack);
                    screenSharing = true;
                }
                console.log(screenStream);
            });
        }

        function stopScreenSharing() {
            if (!screenSharing) return;
            let videoTrack = local_stream.getVideoTracks()[0];
            if (peer) {
                let sender = currentPeer.peerConnection.getSenders().find(function (s) {
                    return s.track.kind === videoTrack.kind;
                });
                sender.replaceTrack(videoTrack);
            }
            screenStream.getTracks().forEach(function (track) {
                track.stop();
            });
            screenSharing = false;
        }
    </script>
    <script src="{{ asset('js/script.js') }}"></script>
    @endsection
