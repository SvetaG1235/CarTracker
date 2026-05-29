<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends Model
{
    // ... (fillable, casts, связи как были) ...
    protected $fillable = ['user_id', 'brand', 'model', 'year', 'vin', 'plate', 'mileage', 'notes'];
    protected $casts = ['year' => 'integer', 'mileage' => 'integer'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function expenses(): HasMany { return $this->hasMany(Expense::class); }
    public function maintenances(): HasMany { return $this->hasMany(Maintenance::class); }
    public function reminders(): HasMany { return $this->hasMany(Reminder::class); }
    public function getFullNameAttribute(): string { return "{$this->brand} {$this->model} ({$this->year})"; }
    public function drivers() { return $this->hasMany(Driver::class); }
public function insurances() { return $this->hasMany(Insurance::class); }
public function serviceCards() { return $this->hasMany(ServiceCard::class); }
    /**
     * 🔥 Автоматически проверяет напоминания при обновлении пробега
     */
    protected static function boot()
    {
        parent::boot();

        static::updated(function (Car $car) {
            // Если пробег изменился и увеличился
            if ($car->isDirty('mileage') && $car->mileage > $car->getOriginal('mileage')) {
                $car->checkMileageReminders();
            }
        });
    }

    public function checkMileageReminders(): void
    {
        $reminders = $this->reminders()
            ->where('is_mileage_based', true)
            ->where('status', '!=', 'dismissed')
            ->whereNotNull('next_mileage_due')
            ->get();

        foreach ($reminders as $reminder) {
            if ($this->mileage >= $reminder->next_mileage_due) {
                $reminder->update([
                    'status' => 'active',
                    'due_date' => now(),
                    'next_mileage_due' => $this->mileage + $reminder->mileage_interval,
                    'title' => $reminder->title . ' (по пробегу)',
                ]);
            }
        }
    }
}
