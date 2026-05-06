<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'price',
        'slug',
        'description',
        'folder',
        'preview_image',
        'main_file',
        'is_premium',
        'is_active',
    ];
}