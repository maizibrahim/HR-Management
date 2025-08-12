<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\designation;
use App\Models\designation\classification;
use App\Models\designation\rank;
use App\Models\Permission;
use App\Models\country;
use App\Models\employee\employeesalarylog;
use Illuminate\Support\Facades\Auth;
Use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\LaravelPdf\Facades\Pdf;

use function Spatie\LaravelPdf\Support\pdf;



class EmployeeRegController extends Controller
{
    Public Function AllEmployee()
    {
        $user = user::where('role','employee')->get();
        return view('employee.registration.all_employee',compact('user'));
    }

    Public Function AddEmployee()
    {
        $classification = classification::all();
        $rank = rank::all();
        $countries = country::all();

        return view('employee.registration..add_employee',compact('classification', 'rank','countries'));
    }

    Public function StoreEmployee(Request $request)
    {
        $userStore = new User();
        $code = rand(0000,9999);
        $userStore->name = $request->name;
        $userStore->username = $request->username;
        $userStore->gender = $request->gender;
        $userStore->idnumber = $request->idnumber;
        $userStore->dob = date('Y-m-d',strtotime($request->dob));
        $userStore->email = $request->email;
        $userStore->code = $code;
        $userStore->phoneNo = $request->phoneNo;
        $userStore->password = bcrypt($code);
        $userStore->role = 'employee';
        $userStore->paddress = $request->paddress;
        $userStore->caddress = $request->caddress;
        $userStore->country = $request->country;
        $userStore->classification_id = $request->classification_id;
        $userStore->rank_id = $request->rank_id;
        $userStore->join_date = date('Y-m-d',strtotime($request->join_date));
        $userStore->save();

        $notification = array(
            'message' => 'Employee created Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('employee.all')->with($notification);
    }

    public function EditEmployee($id)
    {
        $user = User::findOrFail($id);
        $classification = classification::all();
        $rank = rank::all();
        $countries = country::all();
        return view('employee.registration.edit_employee', compact('user','classification', 'rank','countries'));


    }

    public function UpdateEmployee(Request $request, $id){


        $userStore = User::findOrfail($id);
        $userStore->name = $request->name;
        $userStore->username = $request->username;
        $userStore->gender = $request->gender;
        $userStore->idnumber = $request->idnumber;
        $userStore->dob = date('Y-m-d',strtotime($request->dob));
        $userStore->email = $request->email;
        $userStore->phoneNo = $request->phoneNo;
        $userStore->paddress = $request->paddress;
        $userStore->caddress = $request->caddress;
        $userStore->country = $request->country;
        $userStore->classification_id = $request->classification_id;
        $userStore->rank_id = $request->rank_id;
        $userStore->join_date = date('Y-m-d',strtotime($request->join_date));
        $userStore->save();




        $notification = array(
            'message' => 'Employee information updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('employee.all')->with($notification);

    }



    public function DeleteEmployee($id)
    {
        $user = User::findOrFail($id);
        if (!is_null($user)){
            $user->delete();
        }
        $notification = array(
            'message' => 'User deleted successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function DetailEmployee($id)

    {
        $userData = User::findOrFail($id);
        $classification = classification::all();
        $rank = rank::all();
        $countries = country::all();
        return view('employee.registration.detail_employee',compact('userData','rank','classification','countries'));

    }

}
