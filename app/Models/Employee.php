<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends Authenticatable
{
    use HasFactory;
    protected $table = 'employees';
    public $timestamps = false; // Désactiver les timestamps automatiques
    protected $primaryKey = "employeeId";

    // Attributs assignables
    protected $fillable = [
        'email',
        'password',
        // autres colonnes...
    ];

    // Cachez les attributs qui ne doivent pas être affichés, comme le mot de passe
    protected $hidden = [
        'password',
    ];
}
