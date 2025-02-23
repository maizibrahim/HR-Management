<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\employee\supervisor;
use App\Models\User;
class SupervisorController extends Controller
{
    //
    public function AllSupervisor()
    {
        $supervisor = supervisor::all();
        $users = User::all();
        return view ('employee.supervisor.all_supervisor',compact('supervisor','users'));
    }

    public function AddSupervisor()
    {
        $users = User::all();
        return view ('employee.supervisor.add_supervisor',compact('users'));

    }

    public function StoreSupervisor(Request $request)
    {
        $supervisor = new supervisor();
        $supervisor->supervisors_name = $request->supervisors_name;
        $supervisor->save();

        $notification = array(
            'message' => 'Supervisor created successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('supervisor.all')->with($notification);

    }

    public function DeleteSupervisor($id)
    {
        $supervisor = supervisor::findorFail($id);
        $supervisor->delete();

        $notification = array(
            'message' => 'Supervisor deleted successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
}
