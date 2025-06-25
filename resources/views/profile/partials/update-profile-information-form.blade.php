<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and contact details.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input
                id="name"
                name="name"
                type="text"
                class="mt-1 block w-full"
                :value="old('name', $user->name)"
                required
                autofocus
                autocomplete="name"
            />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input
                id="email"
                name="email"
                type="email"
                class="mt-1 block w-full"
                :value="old('email', $user->email)"
                required
                autocomplete="username"
            />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button
                            form="send-verification"
                            class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                        >
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Phone -->
        <div>
            <x-input-label for="phone" :value="__('Phone')" />
            <x-text-input
                id="phone"
                name="phone"
                type="text"
                class="mt-1 block w-full"
                :value="old('phone', $user->phone)"
                required
                autocomplete="tel"
            />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <!-- Date of Birth -->
        <div>
            <x-input-label for="dob" :value="__('Date of Birth')" />
            <x-text-input
                id="dob"
                name="dob"
                type="date"
                class="mt-1 block w-full"
                :value="old('dob', $user->dob)"
                required
                autocomplete="bday"
            />
            <x-input-error class="mt-2" :messages="$errors->get('dob')" />
        </div>

        <!-- Next of Kin -->
        <div>
            <x-input-label for="next_of_kin" :value="__('Next of Kin')" />
            <x-text-input
                id="next_of_kin"
                name="next_of_kin"
                type="text"
                class="mt-1 block w-full"
                :value="old('next_of_kin', $user->next_of_kin)"
                required
                autocomplete="off"
            />
            <x-input-error class="mt-2" :messages="$errors->get('next_of_kin')" />
        </div>

        <!-- Next of Kin Phone Number -->
        <div>
            <x-input-label for="next_of_kin_number" :value="__('Next of Kin Phone Number')" />
            <x-text-input
                id="next_of_kin_number"
                name="next_of_kin_number"
                type="text"
                class="mt-1 block w-full"
                :value="old('next_of_kin_number', $user->next_of_kin_number)"
                required
                autocomplete="tel"
            />
            <x-input-error class="mt-2" :messages="$errors->get('next_of_kin_number')" />
        </div>

        <!-- Region -->
        <div>
            <x-input-label for="region" :value="__('Region')" />
            <x-text-input
                id="region"
                name="region"
                type="text"
                class="mt-1 block w-full"
                :value="old('region', $user->region)"
                required
                autocomplete="address-level1"
            />
            <x-input-error class="mt-2" :messages="$errors->get('region')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>
