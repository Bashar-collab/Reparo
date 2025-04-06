<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    //
    protected $fillable = ['rating', 'data'];

    public function request()
    {
        return $this->belongsTo(Request::class);
    }

    public function reviewer()
    {
        return $this->morphTo();
    }
}
