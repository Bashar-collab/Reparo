<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Pest\ArchPresets\Custom;

class Request extends Model
{
    //
    protected $fillable = ['description', 'image', 'status'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function review()
    {
        return $this->hasMany(Review::class);
    }

    public function payment()
    {
        return $this->hasMany(Payment::class);
    }
}
