<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\employee\supervisor;
use App\Models\leave\leave_allocate;
use App\Models\leave\leave_group;
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

    //Leave Group

    public function AllLeaveGroup()
    {
        $leavegroup = leave_group::all();
        return view('leave.group.all_leave_group',compact('leavegroup'));
    }
    public function AddLeaveGroup()
    {
        return view('leave.group.add_leave_group');
    }

    public function StoreLeaveGroup(Request $request)
    {
        $leavegroup = new leave_group();
        $leavegroup->leave_group = $request->leave_group;
        $leavegroup->save();

        $notification = array(
            'message' => 'Leave group created Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('leave.group.all')->with($notification);
    }

    public function EditLeaveGroup($id)
    {
        $leavegroup = leave_group::findorFail($id);
        return view('leave.group.edit_leave_group',compact('leavegroup'));

    }

    public function DetailLeaveGroup($id)
    {
        $leavegroup = leave_group::findorFail($id);
        $leaveallocation  = leave_allocate::where('leavegroup_id',$id)->get();
        $group = leave_group::all();
        $leave =leave_allocate::all();


        $userData = User::all();
        $leavetype = LeaveType::all();
        $supervisors = supervisor::all();
        return view('leave.group.detail_leave_group',compact('group','leavegroup','leave','leaveallocation','userData','leavetype','supervisors'));

    }

    public function UpdateLeaveGroup(Request $request, $id)
    {
        $leavegroup = leave_group::findorFail($id);
        $leavegroup->leave_group = $request->leave_group;
        $leavegroup->save();

        $notification = array(
            'message' => 'Leave group updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('leave.group.all')->with($notification);

    }

    public function DeleteLeaveGroup($id)
    {
        $leavegroup = leave_group::findorFail($id);
        $leavegroup->delete();
        $notification = array(
            'message' => 'Leave type deleted successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function AllLeaveAllocation()
    {
       $leaveallocation  = leave_allocate::all();
        return view('leave.allocation.all_leave_allocation',compact('leaveallocation'));

    }

    public function AddLeaveAllocation($id)
    {
        $leavegroup = leave_group::findorFail($id);
        $leaveallocation  = leave_allocate::where('leavegroup_id',$id)->get();

        $leave = leave_allocate::all();
        $userData = User::all();
        $leavetype = LeaveType::all();
        $supervisors = supervisor::all();



        return view('leave.allocation.add_leave_allocation',compact('userData','leave','leaveallocation','leavegroup','leavetype','supervisors'));

    }

    public function StoreLeaveAllocation(Request $request)
    {
        $leaveallocation = new leave_allocate();
        $leaveallocation->employee_id = $request->employee_id;
        $leaveallocation->supervisor_id = $request->supervisor_id;
        $leaveallocation->leavegroup_id = $request->leavegroup_id;
        $leaveallocation->leavetype_id = $request->leavetype_id;
        $leaveallocation->total = $request->total;
        $leaveallocation->effective_date = date('Y-m-d',strtotime($request->effective_date));

        $leaveallocation->save();

        $notification = array(
            'message' => 'Employee Added to Leave group Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('leave.group.all')->with($notification);

    }




}
