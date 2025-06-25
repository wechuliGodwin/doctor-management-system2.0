<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, including validation
    | and creation. It uses Laravel's built-in traits for common authentication
    | actions to simplify the code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after successful registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance and apply middleware.
     *
     * @return void
     */
    public function __construct()
    {
        // Ensure that only guests can access the registration actions
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * This validator method defines the rules for validation of user registration data.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:255'],
            'dob' => ['required', 'date'],
            'next_of_kin' => ['required', 'string', 'max:255'],
            'next_of_kin_number' => ['required', 'string', 'max:255'],
            'region' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * This method is responsible for the creation of the user record in the database.
     * It hashes the password before saving it for security purposes.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'dob' => $data['dob'],
            'next_of_kin' => $data['next_of_kin'],
            'next_of_kin_number' => $data['next_of_kin_number'],
            'region' => $data['region'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
