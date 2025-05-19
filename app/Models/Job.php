<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'location',
        'salary_range',
        'is_remote',
        'published_at'
    ];

    protected $casts = [
        'is_remote' => 'boolean',
        'published_at' => 'datetime',
    ];


    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
