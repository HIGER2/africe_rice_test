<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StaffRequest extends Model
{
    use HasFactory;
    protected  $fillable = [
        'employees_id',
        "request_number",
        'marital_status',
        'number_child',
        "depart_date",
        "taking_date",
        'category',
        'status',
        "status_input",
        'total_t_w_f',
        'total_p_e_t',
        'total_f_i_a',
        'total_u',
        'total_p_c_a',
        "room",
        'total_amount',
    ];

    public function children()
    {
        return  $this->hasMany(StaffChild::class, 'staff_requests_id', 'id');
    }

    public function payments()
    {
        return  $this->hasOne(Payment::class, 'staff_requests_id', 'id');
    }

    public function employees()
    {
        return  $this->belongsTo(Employee::class, 'employees_id', 'employeeId');
    }

    protected static function boot()
    {
        parent::boot();
        // Ajoutez un événement `creating` pour générer un numéro de demande unique
        static::creating(function ($demande) {
            $demande->request_number = self::generateUniqueRequestNumber();
        });
    }


    protected static function generateUniqueRequestNumber()
    {
        $max = pow(10, 6) - 1;
        $numeroUnique = str_pad(mt_rand(1, $max), 6, '0', STR_PAD_LEFT);
        do {
            // Générer un numéro de demande aléatoire
            // $requestNumber = 'REQ-' . strtoupper(Str::random(8));

            $numeroUnique = str_pad(mt_rand(1, $max), 6, '0', STR_PAD_LEFT);
            // Vérifier l'unicité dans la base de données
            $exists = self::where('request_number', $numeroUnique)->exists();
        } while ($exists); // Répéter la génération si le numéro existe déjà
        return $numeroUnique;
    }
}