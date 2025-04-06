<?php

namespace App\Models;

use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasFactory;

    protected $fillable = [
        'bio', 
        'working_hours',
        'status' 
    ];

    public function workerSpecialties()
    {
        return $this->hasMany(WorkerSpecialty::class);
    }

    public function documents()
    {
        return $this->hasMany(WorkerDocument::class);
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewer');
    }

    public function users()
    {
        return $this->morphMany(User::class, 'profilable');
    }

    // public function getValidationRules() {
    //     return [
    //         'skills' => 'required|json',
    //         'documents' => 'required|json',
    //     ];
    // }
}
