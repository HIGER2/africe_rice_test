<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'employees';
    public $timestamps = false; // Désactiver les timestamps automatiques
    protected $primaryKey = "employeeId";

    // Attributs assignables
    protected $fillable = [
        "supervisorId",
        'email',
        'password',
        // autres colonnes...
    ];

    // Cachez les attributs qui ne doivent pas être affichés, comme le mot de passe
    protected $hidden = [
        'password',
    ];

    public function supervisor()
    {
        return $this->belongsTo(Employee::class, 'supervisorId');
    }
    public function staff_requests()
    {
        return  $this->hasMany(StaffRequest::class, 'employees_id', 'employeeId');
    }
}