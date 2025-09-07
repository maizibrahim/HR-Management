<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminController extends Controller
{
    //logout the App
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $notification = array(
            'message' => 'User logout Successfully',
            'alert-type' => 'info'
        );
        return redirect('/login')->with($notification);
    }

    //admin user all method
    public function AllUser()
    {
        $alluser = User::all();
        return view('admin.user.all_user',compact('alluser'));
    }

    public function AddUser()
    {
        $roles = Role::all();
        return view('admin.user.add_user',compact('roles'));

    }

    public function StoreUser(Request $request)
    {
        $user = new User();
        $user->role = 'admin';
        $user->save();

        if ($request->roles){
            $user->assignRole($request->roles);
        }

        $notification = array(
            'message' => 'New user added successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('user.all')->with($notification);
    }

    public function EditUser($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('admin.user.edit_user', compact('user','roles'));
    }

    public function UpdateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->role = $request->role;
        $user->save();

        $user->roles()->detach();

        if ($request->roles){
            $user->assignRole($request->roles);
        }
        $notification = array(
            'message' => 'User updated successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('user.all')->with($notification);
    }

    public function DeleteUser($id)
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







}
