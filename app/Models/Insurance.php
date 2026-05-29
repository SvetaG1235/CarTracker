<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Insurance extends Model {
    protected $fillable = [
    'car_id', 'type', 'policy_number', 'company', 'start_date', 'end_date', 'cost', 'policy_file'
];
    protected $casts = ['start_date' => 'date', 'end_date' => 'date', 'cost' => 'decimal:2'];
    public function car(): BelongsTo { return $this->belongsTo(Car::class); }
    
    public function getIsActiveAttribute(): bool {
        return $this->end_date >= now();
    }
}