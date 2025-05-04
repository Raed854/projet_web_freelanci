<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['titre', 'description', 'contenu', 'status', 'files'];

    protected $attributes = [
        'contenu' => '',
    ];
}
