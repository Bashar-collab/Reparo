<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    //
    protected $fillable = ['category_name'];

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function workerSpecialties()
    {
        return $this->hasMany(WorkerSpecialty::class);
    }
}
