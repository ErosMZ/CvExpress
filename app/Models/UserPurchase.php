<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPurchase extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'selected_template_id',
        'status',
        'amount_paid',
        'payment_reference',
        'invoice_number',
        'buyer_name',
        'buyer_email',
        'buyer_nif',
        'buyer_address',
        'hosting_type',
        'subdomain',
        'purchased_at',
        'expires_at',
    ];

    protected $casts = [
        'purchased_at' => 'datetime',
        'expires_at'   => 'datetime',
        'amount_paid'  => 'decimal:2',
    ];

    // Jerarquía de tiers (orden ascendente)
    const TIER_HIERARCHY = ['basic', 'pro', 'super_pro'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function selectedTemplate()
    {
        return $this->belongsTo(Template::class, 'selected_template_id');
    }

    public function accessibleTiers(): array
    {
        $tier = $this->plan->template_tier ?? 'basic';
        $index = array_search($tier, self::TIER_HIERARCHY);
        if ($index === false) return ['basic'];
        return array_slice(self::TIER_HIERARCHY, 0, $index + 1);
    }

    public static function generateInvoiceNumber(): string
    {
        $year = now()->year;
        $last = static::whereYear('created_at', $year)->count() + 1;
        return 'INV-' . $year . '-' . str_pad($last, 4, '0', STR_PAD_LEFT);
    }
}
