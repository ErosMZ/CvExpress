<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [

        'name',
        'slug',
        'description',
        'is_active',

    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIÓN CON TEMPLATES
    |--------------------------------------------------------------------------
    */

    public function templates()
    {
        return $this->hasMany(Template::class);
    }
}