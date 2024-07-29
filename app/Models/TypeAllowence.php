<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeAllowence extends Model
{
    use HasFactory;


    public function staff_category()
    {

        return  $this->hasMany(StaffCategorie::class, 'type_allowence_id', 'id');
    }
}
