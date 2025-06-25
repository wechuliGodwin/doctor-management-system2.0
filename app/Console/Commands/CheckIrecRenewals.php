<?php

// app/Console/Commands/CheckIrecRenewals.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\IrecApplication;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\IrecRenewalNotification;

class CheckIrecRenewals extends Command
{
    protected $signature = 'check:irec-renewals';
    protected $description = 'Check IREC applications nearing their renewal date and send notifications';

    public function handle()
    {
        $applications = IrecApplication::where('notification_sent', false)
            ->where('date_of_renewal', '<=', Carbon::now()->addMonth())
            ->get();

        foreach ($applications as $application) {
            Mail::to($application->researcher->email)->send(new IrecRenewalNotification($application));

            $application->update(['notification_sent' => true]);
        }
    }
}
