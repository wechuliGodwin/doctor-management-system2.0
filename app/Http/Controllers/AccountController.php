<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AccountController extends Controller
{
    
    public function index()
    {
        $user = Auth::user();
        return view('account.index', compact('user'));
    }

    // Show the form for editing the user's profile
    public function edit()
    {
        $user = Auth::user();
        return view('account.edit', compact('user'));
    }

    // Update the user's profile
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            // Add other fields as necessary
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        // Update other fields as necessary
        $user->save();

        return redirect()->route('account.index')->with('success', 'Profile updated successfully.');
    }

    // Delete the user's account
    public function destroy()
    {
        $user = Auth::user();
        $user->delete();

        Auth::logout();
        return redirect('/')->with('success', 'Account deleted successfully.');
    }
}
