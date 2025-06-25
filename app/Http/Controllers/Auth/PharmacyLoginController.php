<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller; // Ensure this is imported
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PharmacyLoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/services/pharmacy'; // Default redirect

    public function __construct()
    {
        // Apply middleware in the constructor
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    // Show the pharmacy login form
    public function showLoginForm()
    {
        return view('auth.pharmacy-login');
    }

    // Override the login logic
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->attemptLogin($request)) {
            $user = Auth::user();

            // Check if the user has a pharmacy-related role
            if (in_array($user->role, ['pharmacy-user', 'pharmacist'])) {
                return $this->sendLoginResponse($request);
            }

            // If not a pharmacy user, logout and redirect back
            Auth::logout();
            return redirect()->route('pharmacy.login')->with('error', 'Only pharmacy users or pharmacists can log in here.');
        }

        return $this->sendFailedLoginResponse($request);
    }

    // Override redirect after login
    protected function redirectTo()
    {
        $role = Auth::user()->role;
        \Log::info("Pharmacy login redirect - User role: {$role}");

        return $role === 'pharmacist' ? '/pharmacist' : '/services/pharmacy';
    }

    // Use the default guard
    protected function guard()
    {
        return Auth::guard();
    }
}
