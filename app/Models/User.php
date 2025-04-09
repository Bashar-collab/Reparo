<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use SadiqSalau\LaravelOTP\Traits\CanVerifyOTP;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone_number', 'password', 'address', 'profile_picture', 'profilable_type', 'profilable_id'
    ];

    protected $hidden = [
        'password',
    ];

    // Define the polymorphic relationship
    public function profilable()
    {
        return $this->morphTo();
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function otpTokens()
    {
        return $this->hasMany(OtpToken::class);
    }

    /**
     * Get the identifier that will be stored in the JWT token.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return an array with custom claims to be added to the JWT token.
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}