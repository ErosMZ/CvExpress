<?php

namespace App\Models;
// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Notifications\VerifyEmailNotification;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;


class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'job_title',
        'phone',
        'location',
        'bio',
        'linkedin_url',
        'website_url',
        'cv_path',
        'cv_original_name',
        'cv_uploaded_at',
        'cv_data',
        'latest_cv_parse_id',
        'profile_photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'cv_uploaded_at'    => 'datetime',
            'password'          => 'hashed',
            'cv_data'           => 'array',
        ];
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmailNotification());
    }

    public function purchases()
    {
        return $this->hasMany(\App\Models\UserPurchase::class);
    }

    public function latestCvParse()
    {
        return $this->belongsTo(\App\Models\CvParse::class, 'latest_cv_parse_id');
    }

    public function cvParses()
    {
        return $this->hasMany(\App\Models\CvParse::class)->latest();
    }

    public function activePurchase()
    {
        return $this->hasOne(\App\Models\UserPurchase::class)
                    ->where('status', 'active')
                    ->with('plan')
                    ->latest();
    }
}
