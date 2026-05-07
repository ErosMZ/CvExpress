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

    /*
    |--------------------------------------------------------------------------
    | RELACIÓN CON CATEGORY
    |--------------------------------------------------------------------------
    */

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}