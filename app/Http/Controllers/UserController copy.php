<?php

namespace App\Http\Controllers;

use App\Models\designation\classification;
use App\Models\designation\rank;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function Profile()
    {
        $id = Auth::user()->id;
        $userData = User::find($id);
        $rank = rank::all();
        $classification = classification::all();

        return view('user.profile_view',compact('userData','rank','classification'));

    }

    public function EditProfile()
    {
        $id = Auth::user()->id;
        $userEdit = User::find($id);
        return view('user.profile_edit',compact('userEdit'));

    }

    public function StoreProfile(Request $request)
    {
        $id = Auth::user()->id;
        $userStore = User::find($id);
        $userStore->name = $request->name;
        $userStore->phoneNo = $request->phoneNo;
        $userStore->paddress = $request->paddress;
        $userStore->caddress = $request->caddress;

        if ($request->file('profile')) {
            $file = $request->file('profile');

            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('upload/profile_images'), $filename);
            $userStore['profile'] = $filename;
        }
        $userStore->save();

        $notification = array(
            'message' => 'User profile updated successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('user.profile')->with($notification);


    }

    public function ChangePassword()
    {
        return view('user.profile_changePassword');

    }

    public function UpdatePassword(Request $request)
    {
        $validateData = $request->validate([
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password',
        ]);
        $hashedPassword = Auth::user()->password;
        if (Hash::check($request->old_password,$hashedPassword )) {
            $users = User::find(Auth::id());
            $users->password = bcrypt($request->new_password);
            $users->save();

            session()->flash('message','Password updated successfully');
            return redirect()->route('user.profile');
        } else {
            session()->flash('message','Old password is not match');
            return redirect()->back();
        }

    }



}
