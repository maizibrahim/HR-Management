<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\designation\classification;
use App\Models\designation\rank;
use Illuminate\Http\Request;
use App\Models\Designation;

class DesignationController extends Controller
{
    //classification
    Public Function AllClassification(){
        $classifications = classification::all();
        return view('employee.designation.classification.all_classification',compact('classifications'));
    }

    public function AddClassification()
    {
        return view('employee.designation.classification.add_classification');
    }

    public function StoreClassification(Request $request)
    {
        $classifications = classification::create([
            'name' =>$request->name
        ]);
        $notification = array(
            'message' => 'classification created Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('classification.all')->with($notification);
    }

    public function EditClassification($id)
    {
        $classifications = classification::findOrFail($id);
        return view('employee.designation.classification.edit_classification', compact('classifications'));
    }

    public function UpdateClassification(Request $request , $id)
    {
        $clasi_id = $request->id;
        classification::findOrFail($clasi_id)->update([
            'name' =>$request->name,
        ]);
        $notification = array(
            'message' => 'Classification updated successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('classification.all')->with($notification);
    }

    public function DeleteClassification($id)
    {
        classification::findOrFail($id)->delete();


        $notification = array(
            'message' => 'Classification deleted successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }


    //All Rank Method started from here

    public function AllRank()
    {
        $ranks = rank::all();
        return view('employee.designation.rank.all_rank',compact('ranks'));
    }
    public function AddRank()
    {
        return view('employee.designation.rank.add_rank');
    }

    public function StoreRank(Request $request)
    {
        $ranks = rank::create([
            'name' =>$request->name,
            'shortcode' =>$request->shortcode,
        ]);
        $notification = array(
            'message' => 'rank created Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('rank.all')->with($notification);
    }

    public function EditRank($id)
    {
        $ranks = rank::findOrFail($id);
        return view('employee.designation.rank.edit_rank', compact('ranks'));
    }

    public function UpdateRank(Request $request)
    {
        $rank_id = $request->id;
        rank::findOrFail($rank_id)->update([
            'name' =>$request->name,
            'shortcode' =>$request->shortcode,
        ]);
        $notification = array(
            'message' => 'rank updated successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('rank.all')->with($notification);
    }

    public function DeleteRank($id)
    {
        rank::findOrFail($id)->delete();


        $notification = array(
            'message' => 'Rank deleted successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

}
