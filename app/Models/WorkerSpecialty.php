<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkerSpecialty extends Model
{
    //

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class);
    }
}
