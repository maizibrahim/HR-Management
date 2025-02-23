<?php

namespace App\Models\leave;

use App\Models\employee\supervisor;
use App\Models\LeaveType;
use App\Models\leave\leave_group;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use function Pest\Laravel\get;

class leave_allocate extends Model
{
    public function EmployeeLeavetype()
    {
        return$this->belongsTo(LeaveType::class,'leavetype_id','id');
    }

    public function EmployeeLeaveGroup()
    {
        return$this->belongsTo(leave_group::class,'leavegroup_id','id');
    }
    public function EmployeeName()
    {
        return$this->belongsTo(User::class,'employee_id','id');
    }

    public function SupervisorName()
    {
        return$this->belongsTo(supervisor::class,'supervisor_id','id');

    }


}
