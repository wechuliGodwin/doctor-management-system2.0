<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class ProfileController extends Controller
{
    /**
     * Display the user's profile management page.
     */
    public function index(Request $request): View
    {
        // Returns the profile index view with the user's data
        return view('profile.seetings', [
            'user' => $request->user(),
        ]);

    }

    /**
     * Display the user's profile edit form.
     */
    public function edit(Request $request): View
    {
        // Returns the profile edit view with the user's data
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Fill the user's data with validated input
        $request->user()->fill($request->validated());

        // If the email has changed, mark the email as unverified
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        // Save the updated user information
        $request->user()->save();

        // Redirect back to the profile edit page with a success status
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Validate the password before deleting the account
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Log the user out before deletion
        Auth::logout();

        // Delete the user account
        $user->delete();

        // Invalidate and regenerate the session token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to the homepage after account deletion
        return Redirect::to('/');
    }
}
