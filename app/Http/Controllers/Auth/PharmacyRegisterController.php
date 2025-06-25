<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PharmacyRegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/services/pharmacy'; // Default, overridden by role

    public function __construct()
    {
        $this->middleware('guest');
    }

    // Show the pharmacy registration form
    public function showPharmacyRegistrationForm()
    {
        return view('auth.pharmacy-register');
    }

    // Validator for registration data (no role field)
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    // Create the user with a default role
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'pharmacy-user', // Default role
        ]);
    }

    // Override redirect after registration
    protected function redirectTo()
    {
        $role = auth()->user()->role;
        \Log::info("Pharmacy registration redirect - User role: {$role}");

        return $role === 'pharmacist' ? '/pharmacist' : '/services/pharmacy';
    }
}
