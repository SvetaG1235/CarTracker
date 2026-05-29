<?php
namespace App\Services;
use App\Models\Reminder;
use App\Models\Car;
use Illuminate\Support\Facades\Log;

class MileageReminderChecker
{
    public function checkForCar(Car $car): void
    {
        $reminders = Reminder::where('car_id', $car->id)
            ->where('is_mileage_based', true)
            ->where('status', '!=', 'dismissed')
            ->whereNotNull('next_mileage_due')
            ->get();

        foreach ($reminders as $reminder) {
            if ($car->mileage >= $reminder->next_mileage_due) {
                $reminder->update([
                    'status' => 'active',
                    'due_date' => now(),
                    'next_mileage_due' => $car->mileage + $reminder->mileage_interval,
                    'title' => $reminder->title . ' (по пробегу)',
                ]);
                Log::info("Mileage reminder triggered: car #{$car->id} | {$reminder->title}");
            }
        }
    }
}