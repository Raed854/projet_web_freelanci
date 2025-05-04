<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposition extends Model
{
    use HasFactory;

    protected $fillable = [
        'contenu',
        'budget',
        'date_creation',
        'date_fin',
    ];

    public $timestamps = true;

    protected $dates = [
        'date_creation',
        'date_fin',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}