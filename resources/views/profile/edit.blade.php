<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- User and Researcher Information Box -->
            <div class="flex flex-col justify-between p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Your Profile Information') }}
                    </h3>
                    <p><strong>Profile and Research Information</strong></p>
                    <div class="mt-4 space-y-4 text-gray-700 dark:text-gray-300">
                        <p><strong>Name:</strong> {{ auth()->user()->name }}</p>
                        <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                        
                        @if($researcher = \App\Models\Researcher::where('email', auth()->user()->email)->first())
                            <p><strong>Researcher Status:</strong> Registered</p>
                            <p><strong>Researcher ID:</strong> {{ $researcher->unique_number }}</p>
                            <p><strong>Researcher Cell:</strong> {{ $researcher->phone }}</p>
                            <p><strong>Institution:</strong> {{ $researcher->institution }}</p>
                        @else
                            <p><strong>Researcher Status:</strong> Not a registered researcher yet</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Update Profile Information -->
            <div class="flex flex-col justify-between p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password -->
            <div class="flex flex-col justify-between p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Create Researcher Profile or Display Existing Profile -->
            <div class="flex flex-col justify-between p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Create Researcher Profile') }}
                    </h3>

                    @if($researcher)
                        <p class="text-red-600 dark:text-red-400 mt-4">
                            {{ __('Research profile already exists. You cannot create another one.') }}
                        </p>
                        <!-- Display existing profile data -->
                        <div class="mt-4 space-y-4 text-gray-700 dark:text-gray-300">
                            <p><strong>Phone Number:</strong> {{ $researcher->phone }}</p>
                            <p><strong>Institution:</strong> {{ $researcher->institution }}</p>
                        </div>
                    @else
                        <form method="POST" action="{{ route('researchers.transfer') }}" class="mt-4 space-y-4">
                            @csrf

                            <!-- Phone Number -->
                            <div>
                                <x-input-label for="phone" :value="__('Phone Number')" />
                                <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" value="{{ old('phone') }}" required autocomplete="tel" />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>

                            <!-- Institution -->
                            <div>
                                <x-input-label for="institution" :value="__('Institution')" />
                                <x-text-input id="institution" name="institution" type="text" class="mt-1 block w-full" value="{{ old('institution') }}" required autocomplete="organization" />
                                <x-input-error :messages="$errors->get('institution')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end">
                                <x-primary-button>
                                    {{ __('Create Researcher Profile') }}
                                </x-primary-button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
