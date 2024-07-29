<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeChild extends Model
{
    use HasFactory;

    protected  $fillable = [
        'employee_informatons_id',
        'age',
        'sex',
    ];
}
