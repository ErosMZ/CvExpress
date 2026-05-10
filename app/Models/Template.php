<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $fillable = [

        'category_id',
        'name',
        'slug',
        'description',
        'folder',
        'preview_image',
        'main_file',
        'is_premium',
        'price',
        'is_featured',
        'is_active',

    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getPreviewHtmlUrlAttribute(): ?string
    {
        $publicDir = public_path('previews/' . $this->slug);

        if (file_exists($publicDir . '/index.html')) {
            return asset('previews/' . $this->slug . '/index.html');
        }

        foreach (glob($publicDir . '/*/index.html') ?: [] as $p) {
            $sub = basename(dirname($p));
            return asset('previews/' . $this->slug . '/' . $sub . '/index.html');
        }

        return null;
    }
}