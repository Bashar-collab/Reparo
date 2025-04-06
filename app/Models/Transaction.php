<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
