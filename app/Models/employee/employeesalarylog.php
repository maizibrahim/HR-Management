<?php

namespace App\Models\employee;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class employeesalarylog extends Model
{
    public function EmploySalary()
    {
        return $this->belongsTo(User::class,'employee_id','id');
    }
}
