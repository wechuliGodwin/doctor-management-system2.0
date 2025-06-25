    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        function chat() {
            alert('Chat feature is under development.');
        }

        function call() {
            alert('Call feature is under development.');
        }

        function document() {
            alert('Document feature is under development.');
        }

        document.getElementById('fileInput').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                alert('You selected: ' + file.name);
            }
        });
    </script>
