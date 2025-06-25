<?php
namespace App\Models;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Registration extends Model
{
    // Assuming no direct database table for this model
    protected $table = null;

    public static function registerUser($data)
    {
        try {
            // Validate the data or assume it's already validated
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'], // Add phone number here
                'password' => Hash::make($data['password']),
            ]);

            if ($user) {
                $user->sendEmailVerificationNotification();
                return $user;
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Registration failed: ' . $e->getMessage());
            return null;
        }
    }
}
