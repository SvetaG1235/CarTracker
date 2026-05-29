<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceCard extends Model {
    protected $fillable = [
        'car_id', 'workshop_name', 'service_card_number', 'barcode_image',
        'contact_phone', 'address', 'last_visit', 'notes'
    ];
    protected $casts = ['last_visit' => 'date'];
    public function car(): BelongsTo { return $this->belongsTo(Car::class); }
}