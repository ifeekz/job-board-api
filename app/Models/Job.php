<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use HasFactory, Searchable, SoftDeletes;
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

    public function toSearchableArray()
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
        ];
    }


    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
