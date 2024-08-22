<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_requests_id',
        'finance_id',
        'staff_id',
        'status_payment',
        'amount',
        'date_payment',
    ];
}