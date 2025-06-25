<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log; // Import for logging
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the incoming request data
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Log the initial reset request details
        Log::info('Password reset attempt', [
            'email' => $request->email,
            'token' => $request->token,
        ]);

        // Attempt to reset the user's password
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => $request->password, // Directly assign; model handles hashing
                    'remember_token' => Str::random(60),
                ])->save();

                // Fire Password Reset event
                event(new PasswordReset($user));

                // Log successful password reset for the user
                Log::info('Password reset successful for user.', ['email' => $user->email]);
            }
        );

        // Redirect the user based on the password reset status
        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('login')
                ->with('status', __('Your password has been successfully reset. Please log in with your new credentials.'));
        } else {
            // Log error if password reset fails
            Log::error('Password reset failed: Invalid credentials or token.', [
                'email' => $request->email,
                'status' => $status,
                'token' => $request->token
            ]);

            return back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
        }
    }
}
