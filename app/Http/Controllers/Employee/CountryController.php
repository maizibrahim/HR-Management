<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\country;
use Illuminate\Http\Request;


class CountryController extends Controller
{
    public function AllCountry()
    {
        $country = country::all();
        return view('employee.country.all_country',compact('country'));
    }

    public function AddCountry()
    {

        return view('employee.country.add_country');
    }

    public function StoreCountry(Request $request)
    {
        $country = new country();
        $country->name = $request->name;
        $country->save();

        $notification = array(
            'message' => 'New user added successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('country.all')->with($notification);
    }

    public function EditCountry($id)
    {
        $country = country::findOrFail($id);
        return view('employee.country.edit_country', compact('country'));
    }

    public function UpdateCountry(Request $request, $id){
        $country = country::findOrFail($id);
        $country->name = $request->name;
        $country->save();

        $notification = array(
            'message' => 'Country name updated successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('country.all')->with($notification);

    }

    public function DeleteCountry($id)
    {
        $country = country::findOrFail($id)->delete();
        $notification = array(
            'message' => 'Country name deleted successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);

    }
}
