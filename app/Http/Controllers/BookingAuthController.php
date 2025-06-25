<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\BookingUserAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;

class BookingAuthController extends Controller
{
    public function __construct()
    {
        // Apply auth middleware only to methods that require authentication
        $this->middleware('auth:booking')->except([
            'showBookingLoginForm',
            'bookingLogin',
            'showBookingForgotPasswordForm',
            'sendBookingResetLinkEmail',
            'showBookingResetPasswordForm',
            'resetBookingPassword'
        ]);

        // Apply role-based middleware only to user management methods
        $this->middleware(function ($request, $next) {
            $user = Auth::guard('booking')->user();
            if (!$user || !in_array($user->role, ['admin', 'superadmin'])) {
                abort(403, 'Unauthorized access.');
            }
            return $next($request);
        })->only([
                    'index',
                    'create',
                    'store',
                    'edit',
                    'update',
                    'showChangePasswordForm',
                    'changePassword',
                    'toggleStatus',
                    'destroy'
                ]);
    }

    /**
     * Display a listing of users based on role permissions
     */
    public function index(Request $request)
    {
        $currentUser = Auth::guard('booking')->user();
        $query = BookingUserAuth::query();

        // Role-based filtering
        if ($currentUser->role === 'admin') {
            // Admin can only see users from their hospital branch
            $query->where('hospital_branch', $currentUser->hospital_branch);
        }
        // Superadmin can see all users (no additional filtering)

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Role filter
        if ($request->filled('role_filter')) {
            $query->where('role', $request->get('role_filter'));
        }

        // Hospital branch filter (only for superadmin)
        if ($request->filled('branch_filter') && $currentUser->role === 'superadmin') {
            $query->where('hospital_branch', $request->get('branch_filter'));
        }

        // Status filter
        if ($request->filled('status_filter')) {
            $status = $request->get('status_filter');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        $hospital_branches = $this->getHospitalBranchEnumValues();
        $roles = $this->getUserRolesEnumValues();

        return view('booking.auth.users.index', compact('users', 'hospital_branches', 'roles', 'currentUser'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $hospital_branches = $this->getHospitalBranchEnumValues();
        $roles = $this->getUserRolesEnumValues();

        return view('booking.auth.users.create', compact('hospital_branches', 'roles'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $currentUser = Auth::guard('booking')->user();

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:booking_user_auth'],
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
            ],
            'role' => ['required', 'in:' . implode(',', $this->getUserRolesEnumValues())],
            'hospital_branch' => ['required', 'in:' . implode(',', $this->getHospitalBranchEnumValues())],
        ]);

        // Admins can only create users for their own hospital branch
        if ($currentUser->role === 'admin') {
            $validated['hospital_branch'] = $currentUser->hospital_branch;
        }

        $user = BookingUserAuth::create([
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'hospital_branch' => $validated['hospital_branch'],
            'is_active' => true,
        ]);

        Log::info('User created', [
            'created_user_id' => $user->id,
            'created_by' => $currentUser->id,
            'email' => $user->email,
        ]);

        return redirect()->route('booking.auth.users.index')->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing a user
     */
    public function edit($id)
    {
        $currentUser = Auth::guard('booking')->user();
        $user = BookingUserAuth::findOrFail($id);

        // Check if admin can edit this user (same hospital branch)
        if ($currentUser->role === 'admin' && $user->hospital_branch !== $currentUser->hospital_branch) {
            abort(403, 'You can only edit users from your hospital branch.');
        }

        $hospital_branches = $this->getHospitalBranchEnumValues();
        $roles = $this->getUserRolesEnumValues();

        return view('booking.auth.users.edit', compact('user', 'hospital_branches', 'roles', 'currentUser'));
    }

    /**
     * Update user details
     */
    public function update(Request $request, $id)
    {
        $currentUser = Auth::guard('booking')->user();
        $user = BookingUserAuth::findOrFail($id);

        // Check permissions
        if ($currentUser->role === 'admin' && $user->hospital_branch !== $currentUser->hospital_branch) {
            abort(403, 'You can only edit users from your hospital branch.');
        }

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('booking_user_auth')->ignore($user->id)],
            'role' => ['required', 'in:' . implode(',', $this->getUserRolesEnumValues())],
            'hospital_branch' => ['required', 'in:' . implode(',', $this->getHospitalBranchEnumValues())],
            'is_active' => ['boolean'],
        ]);

        // Admins cannot change hospital branch
        if ($currentUser->role === 'admin') {
            unset($validated['hospital_branch']);
        }

        $user->update($validated);

        Log::info('User updated', [
            'updated_user_id' => $user->id,
            'updated_by' => $currentUser->id,
        ]);

        return redirect()->route('booking.auth.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Show change password form
     */
    public function showChangePasswordForm($id)
    {
        $currentUser = Auth::guard('booking')->user();
        $user = BookingUserAuth::findOrFail($id);

        // Check permissions
        if ($currentUser->role === 'admin' && $user->hospital_branch !== $currentUser->hospital_branch) {
            abort(403, 'You can only change passwords for users from your hospital branch.');
        }

        return view('booking.auth.users.change-password', compact('user', 'currentUser'));
    }

    /**
     * Change user password
     */
    public function changePassword(Request $request, $id)
    {
        $currentUser = Auth::guard('booking')->user();
        $user = BookingUserAuth::findOrFail($id);

        // Check permissions
        if ($currentUser->role === 'admin' && $user->hospital_branch !== $currentUser->hospital_branch) {
            abort(403, 'You can only change passwords for users from your hospital branch.');
        }

        $request->validate([
            'new_password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
            ],
        ]);

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        Log::info('Password changed', [
            'user_id' => $user->id,
            'changed_by' => $currentUser->id,
        ]);

        return back()->with('success', 'Password changed successfully.');
    }

    /**
     * Toggle user active status
     */
    public function toggleStatus($id)
    {
        $currentUser = Auth::guard('booking')->user();
        $user = BookingUserAuth::findOrFail($id);

        // Check permissions
        if ($currentUser->role === 'admin' && $user->hospital_branch !== $currentUser->hospital_branch) {
            abort(403, 'You can only manage users from your hospital branch.');
        }

        // Prevent users from deactivating themselves
        if ($user->id === $currentUser->id) {
            return back()->with('error', 'You cannot change your own status.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';

        Log::info("User {$status}", [
            'user_id' => $user->id,
            'changed_by' => $currentUser->id,
        ]);

        return back()->with('success', "User {$status} successfully.");
    }
    

    /**
     * Delete user (soft delete or permanent based on requirements)
     */
    public function destroy($id)
    {
        $currentUser = Auth::guard('booking')->user();
        $user = BookingUserAuth::findOrFail($id);

        // Only superadmin can delete users
        if ($currentUser->role !== 'superadmin') {
            abort(403, 'Only superadmin can delete users.');
        }

        // Prevent users from deleting themselves
        if ($user->id === $currentUser->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        Log::info('User deleted', [
            'deleted_user_id' => $user->id,
            'deleted_by' => $currentUser->id,
        ]);

        return back()->with('success', 'User deleted successfully.');
    }

    /**
     * Show booking login form
     */
    public function showBookingLoginForm()
    {
        Log::info('Accessing booking login form', ['intended' => session('url.intended', 'none')]);
        return view('booking.auth.login');
    }

    /**
     * Handle booking login
     */
    public function bookingLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Check if user exists and is active
        $user = BookingUserAuth::where('email', $credentials['email'])->first();
        if (!$user) {
            Log::warning('Login failed: User not found', ['email' => $credentials['email']]);
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        if (!$user->is_active) {
            Log::warning('Login failed: User is inactive', ['email' => $credentials['email']]);
            return back()->withErrors([
                'email' => 'Your account is inactive. Please contact an administrator.',
            ])->onlyInput('email');
        }

        if (Auth::guard('booking')->attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::guard('booking')->user();

            Log::info('Login successful for booking', [
                'email' => $credentials['email'],
                'user_id' => $user->id,
                'hospital_branch' => $user->hospital_branch ?? 'none',
                'session_id' => session()->getId()
            ]);

            $intended = session('url.intended', route('booking.dashboard'));
            session()->forget('url.intended');
            return redirect()->intended($intended)->with('success', 'Logged in successfully');
        }

        Log::warning('Login failed for booking', [
            'email' => $credentials['email'],
            'user_exists' => $user ? 'yes' : 'no',
        ]);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle booking logout
     */
    public function bookingLogout(Request $request)
    {
        Log::info('Logout attempt for booking', [
            'user_id' => Auth::guard('booking')->id(),
            'session_id' => session()->getId()
        ]);

        Auth::guard('booking')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info('Logout successful for booking');
        return redirect()->route('booking.login')->with('success', 'Logged out successfully');
    }

    /**
     * Show forgot password form
     */
    public function showBookingForgotPasswordForm()
    {
        return view('booking.auth.forgot-password');
    }

    /**
     * Send password reset link
     */
    public function sendBookingResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::broker('booking_users')->sendResetLink(
            $request->only('email')
        );

        Log::info('Password reset link status for booking', ['status' => $status]);

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Show password reset form
     */
    public function showBookingResetPasswordForm($token)
    {
        Log::info('Showing reset password form for booking', ['token' => $token]);
        return view('booking.auth.reset-password', ['token' => $token]);
    }

    /**
     * Reset password
     */
    public function resetBookingPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'min:6',
                'confirmed',
                'regex:/^(?=.*[A-Z])(?=.*\W).+$/'
            ],
        ], [
            'password.regex' => 'The password must contain at least one uppercase letter and one symbol.',
        ]);

        Log::info('Password reset attempt for booking', ['email' => $request->email]);

        $status = Password::broker('booking_users')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
                $user->save();
                Log::info('Password reset successful for booking', ['email' => $user->email]);
            }
        );

        Log::info('Password reset status for booking', ['status' => $status]);

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('booking.login')->with('status', 'Password has been reset successfully.')
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Get hospital branch enum values
     */
    protected function getHospitalBranchEnumValues()
    {
        return ['kijabe', 'westlands', 'naivasha', 'marira'];
    }

    /**
     * Get user roles enum values
     */
    protected function getUserRolesEnumValues()
    {
        return ['user', 'admin', 'superadmin'];
    }
}