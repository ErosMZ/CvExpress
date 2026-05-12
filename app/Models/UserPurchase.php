<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPurchase extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'status',
        'amount_paid',
        'payment_reference',
        'hosting_type',
        'subdomain',
        'purchased_at',
    ];

    protected $casts = [
        'purchased_at' => 'datetime',
        'amount_paid'  => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
