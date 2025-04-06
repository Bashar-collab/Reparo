<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkerDocument extends Model
{
    //
    protected $fillable = ['document_image', 'document_type', 'document_number', 'expiration_date'];

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }
}
