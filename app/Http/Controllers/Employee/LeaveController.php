<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\designation;
use App\Models\designation\classification;
use App\Models\designation\rank;
use App\Models\employee\Employeesleaves;
use App\Models\LeaveType;
use App\Models\country;

class LeaveController extends Controller
{
    public function AllLeaveType()
    {
        $leavetype = LeaveType::all();
        return view('leave.type.all_leave_type',compact('leavetype'));
    }

    public function AddLeaveType()
    {
        return view('leave.type.add_leave_type');

    }

    public function StoreLeaveType(Request $request)
    {
        $leavetype = new LeaveType();
        $leavetype->leave_code = $request->leave_code;
        $leavetype->Leave_name = $request->Leave_name;
        $leavetype->leave_total = $request->leave_total;
        $leavetype->save();

        $notification = array(
            'message' => 'Leave type created successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('leave.type.all')->with($notification);
    }

    public function EditLeaveType($id)
    {
        $leavetype = LeaveType::findorFail($id);
        return view('leave.type.edit_leave_type',compact('leavetype'));
    }

    public function UpdateLeaveType(Request $request,$id)
    {
        $leavetype = LeaveType::findorFail($id);
        $leavetype->leave_code = $request->leave_code;
        $leavetype->Leave_name = $request->Leave_name;
        $leavetype->leave_total = $request->leave_total;
        $leavetype->save();

        $notification = array(
            'message' => 'Leave type updated successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('leave.type.all')->with($notification);
    }

    public function DeleteLeaveType($id)
    {
        $leavetype = LeaveType::findorFail($id);
        $leavetype->delete();

        $notification = array(
            'message' => 'Leave type deleted successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }



}
