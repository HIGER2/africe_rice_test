<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceEmail extends Model
{
    use HasFactory;

    protected $fillable = [
        "service",
        'email',
        'admin',
        // autres colonnes...
    ];
}
