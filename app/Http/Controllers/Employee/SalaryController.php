<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\employee\employeesalarylog;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    //
    public function AllSalary()
    {
        $user = user::where('role','employee')->get();
        $salaries = employeesalarylog::all();
        return view('employee.salary.all_emp_salary',compact('user','salaries'));

    }

    Public Function AddSalary()
    {
        $user = user::where('role','employee')->get();
        $salary = employeesalarylog::all();
        return view('employee.salary.add_emp_salary',compact('user','salary'));
    }

    public function StoreSalary(Request $request)
    {
        $salary = new employeesalarylog();
        $salary->employee_id = $request->employee_id;
        $salary->basic_salary = $request->basic_salary;
        $salary->attendance_allowance = $request->attendance_allowance;
        $salary->service_allowance = $request->service_allowance;
        $salary->job_allowance = $request->job_allowance;
        $salary->save();

        $notification = array(
            'message' => 'Employee salary created Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('salary.all')->with($notification);
    }

    public function EditSalary($id)
    {
        $salaries = employeesalarylog::findOrFail($id);
        $user = user::where('role','employee')->get();

        return view('employee.salary.edit_emp_salary',compact('salaries','user'));
    }

    public function UpdateSalary(Request $request, $id)
    {
        $salaryupdate = employeesalarylog::findOrfail($id);
        $salaryupdate->basic_salary = $request->basic_salary;
        $salaryupdate->attendance_allowance = $request->attendance_allowance;
        $salaryupdate->service_allowance = $request->service_allowance;
        $salaryupdate->job_allowance = $request->job_allowance;
        $salaryupdate->save();

        $notification = array(
            'message' => 'Employee salary update Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('salary.all')->with($notification);

    }

    public function DeleteSalary($id)
    {
        $salary = employeesalarylog::findorfail($id);
        if (!is_null($salary)){
            $salary->delete();
        }
        $notification = array(
            'message' => 'Salary information deleted',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);

    }


}
