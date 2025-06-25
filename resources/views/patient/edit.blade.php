<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- User Information Box -->
            <div class="flex flex-col justify-between p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Your Profile Information') }}
                    </h3>
                    <p><strong>Profile Information</strong></p>
                    <div class="mt-4 space-y-4 text-gray-700 dark:text-gray-300">
                        <p><strong>Name:</strong> {{ auth()->user()->name }}</p>
                        <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Update Profile Information -->
            <div class="flex flex-col justify-between p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include(patient.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password -->
            <div class="flex flex-col justify-between p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('patient.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
