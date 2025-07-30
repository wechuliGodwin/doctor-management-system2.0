<?php

namespace App\Providers;

use App\Models\NewAppointment;
use App\Models\ReviewAppointment;
use App\Models\PostOpAppointment;
use App\Models\BkMessaging;
use App\Models\ExternalPendingApproval;
use App\View\Composers\ReminderCountComposer;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // View::composer('*', function ($view) {
        //     if (Auth::guard('booking')->check()) {
        //         $userBranch = Auth::guard('booking')->user()->hospital_branch;
        //         $alertsCount = BkMessaging::where('active', 1)
        //             ->where('hospital_branch', $userBranch)
        //             ->count();

        //         $view->with('alertsCount', $alertsCount);
        //     }
        // });

        // View::composer(['layouts.dashboard', 'layouts.sidebar'], ReminderCountComposer::class);
    }
}
