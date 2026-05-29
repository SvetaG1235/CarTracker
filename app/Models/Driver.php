<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Driver extends Model {
    protected $fillable = ['car_id', 'full_name', 'license_number', 'phone', 'is_primary'];
    public function car(): BelongsTo { return $this->belongsTo(Car::class); }
}