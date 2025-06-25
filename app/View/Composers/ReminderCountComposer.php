<?php

namespace App\View\Composers;

use App\Models\NewAppointment;
use App\Models\ReviewAppointment;
use App\Models\PostOpAppointment;
use App\Models\ExternalApproved;
use Illuminate\View\View;

class ReminderCountComposer
{
    public function compose(View $view)
    {
        $today = now()->startOfDay();
        $start = $today;
        $end = $today->addDays(7)->endOfDay();

        $models = [
            NewAppointment::class,
            ReviewAppointment::class,
            PostOpAppointment::class,
            ExternalApproved::class,
        ];

        $upcomingCount = 0;
        $missedCount = 0;

        foreach ($models as $model) {
            $query = $model::whereBetween('appointment_date', [$start, $end])
                ->where('reminder_cleared', false);
            if ($model === ExternalApproved::class) {
                $query->where('patient_notified', false);
            }
            $upcomingCount += $query->count();

            $missedCount += $model::where('appointment_date', '<', $today)
                ->whereNotIn('appointment_status', ['honoured', 'cancelled'])
                ->where('reminder_cleared', false)
                ->count();
        }

        $reminderCount = $upcomingCount + $missedCount;

        $view->with('reminderCount', $reminderCount);
    }
}