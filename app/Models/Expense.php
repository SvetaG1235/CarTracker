<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    protected $fillable = ['user_id', 'car_id', 'category', 'amount', 'date', 'description', 'receipt_path'];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
    ];

    public function car(): BelongsTo { return $this->belongsTo(Car::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }

    public static function getTotalByPeriod($userId, $period = 'month')
    {
        $groupBy = match($period) {
            'week' => '%Y-%u',
            'month' => '%Y-%m',
            'year' => '%Y',
            default => '%Y-%m'
        };

        return self::where('user_id', $userId)
            ->selectRaw("DATE_FORMAT(date, '$groupBy') as period, SUM(amount) as total")
            ->groupBy('period')
            ->orderBy('period')
            ->get();
    }
}