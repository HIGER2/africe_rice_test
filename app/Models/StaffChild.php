<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffChild extends Model
{
    use HasFactory;

    protected  $fillable = [
        'staff_requests_id',
        'age',
        'sex',
    ];
}