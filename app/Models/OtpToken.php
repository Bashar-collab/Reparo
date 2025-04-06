<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpToken extends Model
{
    //

    protected $fillable = ['otp'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
