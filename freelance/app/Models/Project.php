<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
        'author_id',
    ];

    public function propositions()
    {
        return $this->hasMany(Proposition::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}