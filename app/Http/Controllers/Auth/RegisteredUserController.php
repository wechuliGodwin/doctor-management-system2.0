<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * This method manages the registration process, validates the user data,
     * creates the user account, and logs the user in.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the incoming request data
        $this->validator($request->all())->validate();

        // Create the user with validated data
        $user = $this->createUser($request->all());

        // Fire the Registered event
        event(new Registered($user));

        // Send notification email to the specified address
        \Mail::to('telemedicine@kijabehospital.org')->send(new \App\Mail\NewUserNotification($user));

        // Log the user in
        Auth::login($user);

        // Redirect to the dashboard
        return redirect()->route('dashboard');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email'
            ],
            
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            
            'phone' => [
                'required',
                'string',
                'max:15',
                'regex:/^[0-9]+$/'
            ],
            
            'dob' => ['required', 'date'],
            
            'next_of_kin' => ['required', 'string', 'max:255'],
            
            'next_of_kin_number' => [
                'required',
                'string',
                'max:15',
                'regex:/^[0-9]+$/'
            ],
            
            'region' => ['required', 'string', 'max:255'],
        ], [
            'phone.regex' => 'The phone number must contain only digits.',
            'next_of_kin_number.regex' => 'The next of kin number must contain only digits.',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * The password is assigned in plain text here and will be hashed
     * by the User model's mutator before saving to the database.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function createUser(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'], // Plain password; model mutator hashes it
            'phone' => $data['phone'],
            'dob' => $data['dob'],
            'next_of_kin' => $data['next_of_kin'],
            'next_of_kin_number' => $data['next_of_kin_number'],
            'region' => $data['region'],
        ]);
    }
}
