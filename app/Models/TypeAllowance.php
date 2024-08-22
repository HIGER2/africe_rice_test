<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeAllowance extends Model
{
    use HasFactory;

    

    public function staff_categories()
    {

        return  $this->hasMany(StaffCategorie::class, 'type_allowance_id', 'id');
    }
}