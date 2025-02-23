<?php

namespace App\Models\employee;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class supervisor extends Model
{
    public function EmploySupervisor()
    {
        return$this->belongsTo(User::class,'name','supervisors_name');
    }
}
