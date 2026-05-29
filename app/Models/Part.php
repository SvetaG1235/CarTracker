<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $fillable = ['name', 'brand', 'sku', 'price', 'compatibility', 'description'];

    protected $casts = [
        'price' => 'decimal:2',
        'compatibility' => 'array',
    ];

    public function scopeCompatibleWith($query, $brand, $model, $year = null)
    {
        $pattern = "%{$brand}%{$model}%";
        if ($year) $pattern .= ":{$year}";
        return $query->where('compatibility', 'LIKE', $pattern);
    }
}