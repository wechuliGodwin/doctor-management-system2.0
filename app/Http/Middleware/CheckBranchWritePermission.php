<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckBranchWritePermission
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('booking')->user();
        $selectedBranch = session('selected_branch', $user->hospital_branch);

        // Superadmins have full access
        if ($user->role === 'superadmin') {
            return $next($request);
        }

        // Check branch_permissions for the selected branch
        $branchPermissions = $user->branch_permissions ?? [$user->hospital_branch => 'read-write'];
        if (!isset($branchPermissions[$selectedBranch]) || $branchPermissions[$selectedBranch] !== 'read-write') {
            Log::warning('Unauthorized write attempt', [
                'user_id' => $user->id,
                'branch' => $selectedBranch,
                'permissions' => $branchPermissions,
            ]);
            return redirect()->back()->with('error', 'You do not have write permission for the selected branch.');
        }

        return $next($request);
    }
}