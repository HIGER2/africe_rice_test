<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeInformaton extends Model
{
    use HasFactory;
    protected  $fillable = [
        'employees_id',
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

        return  $this->hasMany(EmployeeChild::class, 'employee_informatons_id', 'id');
    }

    public function employees()
    {

        return  $this->belongsTo(Employee::class, 'employees_id', 'employeeId');
    }
}