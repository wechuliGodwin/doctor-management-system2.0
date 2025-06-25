@extends('layouts.header')
@section('scripts')
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
@endsection




@section('content')
    @include('livewire.partials.appointments')
    @include('livewire.partials.schedules')
    {{-- @include('livewire.partials.todays-appointments')
    @include('livewire.partials.appointment-requests')
    @include('livewire.partials.patients-details-modals')
    @include('livewire.partials.appointment-request-modals')
    @include('livewire.partials.patients-details-modals') --}}
@endsection

