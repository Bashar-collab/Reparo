<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Pest\ArchPresets\Custom;

class Referral extends Model
{
    //

    public function referrer()
    {
        return $this->belongsTo(Customer::class, 'referrer_id');
    }

    public function referred()
    {
        return $this->belongsTo(Customer::class, 'referred_id');
    }
}
