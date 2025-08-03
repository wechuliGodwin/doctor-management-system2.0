<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EnsureValidBranch
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('booking')->user();
        $selectedBranch = session('selected_branch', $user->hospital_branch);

        if (!$user) {
            return redirect()->route('booking.login');
        }

        $validBranches = $user->role === 'superadmin' ? $user->switchable_branches : [$user->hospital_branch];
        if (!in_array($selectedBranch, $validBranches)) {
            Log::warning('Invalid selected branch', [
                'user_id' => $user->id,
                'selected_branch' => $selectedBranch,
                'valid_branches' => $validBranches,
            ]);
            session(['selected_branch' => $user->hospital_branch]);
            return redirect()->back()->with('error', 'Invalid branch selected. Reverted to default branch.');
        }

        return $next($request);
    }
}