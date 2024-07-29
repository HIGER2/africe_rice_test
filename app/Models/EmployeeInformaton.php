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
        'category',
        'status'
    ];


    public function children()
    {

        return  $this->hasMany(EmployeeChild::class, 'employee_informatons_id', 'id');
    }
}
