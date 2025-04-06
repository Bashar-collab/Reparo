<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    //
    protected $fillable = ['name', 'type', 'description', 'currency', 'transaction_fee', 'status'];
}
