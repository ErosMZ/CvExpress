<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'price',
        'color',
        'badge_label',
        'features',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'features'  => 'array',
        'is_active' => 'boolean',
        'price'     => 'decimal:2',
    ];

    public function purchases()
    {
        return $this->hasMany(UserPurchase::class);
    }
}
