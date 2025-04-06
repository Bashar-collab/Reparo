<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    //
    protected $fillable = ['service_name', 'description'];

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class);
    }
}
