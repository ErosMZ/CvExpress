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

    /**
     * Niveles de plantilla accesibles por el plan CON HOSTING (categoría
     * "template"). No aplica a compras "web_download": esas son de UNA
     * plantilla concreta, comprada por su propio precio — usa
     * `canUseTemplate()` para esas.
     */
    public function accessibleTiers(): array
    {
        $tier = $this->plan->template_tier ?? 'basic';
        $index = array_search($tier, self::TIER_HIERARCHY);
        if ($index === false) return ['basic'];
        return array_slice(self::TIER_HIERARCHY, 0, $index + 1);
    }

    /**
     * ¿Puede el usuario usar/descargar esta plantilla con esta compra?
     * - Categoría "template": cualquier plantilla dentro del nivel del plan.
     * - Categoría "web_download": solo la plantilla concreta que compró
     *   (cada plantilla tiene su propio precio — no es un pase universal).
     */
    public function canUseTemplate(?Template $template): bool
    {
        if (! $template) return false;

        if (($this->plan->category ?? null) === 'web_download') {
            return $this->selected_template_id === $template->id;
        }

        return in_array($template->plan_tier, $this->accessibleTiers(), true);
    }

    /**
     * ¿Este plan incluye hosting/subdominio? Solo los de categoría
     * "template". Los "web_download" son solo para descargar el ZIP.
     */
    public function canHost(): bool
    {
        return ($this->plan->category ?? null) === 'template';
    }

    public static function generateInvoiceNumber(): string
    {
        $year = now()->year;
        $last = static::whereYear('created_at', $year)->count() + 1;
        return 'INV-' . $year . '-' . str_pad($last, 4, '0', STR_PAD_LEFT);
    }
}
