<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    //
    Public Function AllPermission(){

        $permission = Permission::all();
        return view('admin.auth.all_permission',compact('permission'));

    }

    Public Function AddPermission(){

        return view('admin.auth.add_permission');

    }

    Public Function StorePermission(Request $request){

        $permission = Permission::create([
            'name' =>$request->name,
            'group_name' =>$request->group_name,

        ]);

        $notification = array(
            'message' => 'Permission created Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('permission.all')->with($notification);

    }

    public function EditPermission($id)
    {
        $permission = Permission::findOrFail($id);
        return view('admin.auth.edit_permission', compact('permission'));

    }

    public function UpdatePermission (Request $request)
    {
        $per_id = $request->id;
        Permission::findOrFail($per_id)->update([
            'name' =>$request->name,
            'group_name' =>$request->group_name,
        ]);

        $notification = array(
            'message' => 'Permission updated successfully',
            'alert-type' => 'success'
        );

        return redirect('all/permission')->with($notification);
    }

    public function DeletePermission($id)
    {
        Permission::findOrFail($id)->delete();


        $notification = array(
            'message' => 'Permission deleted successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }


    /////////roll method sarted from here /////

    Public Function AllRole(){

        $roles = Role::all();
        return view('admin.auth.all_roles',compact('roles'));

    }

    public function AddRole()
    {
        return view('admin.auth.add_roles');
    }

    public function StoreRole(Request $request)
    {
        Role::create([
            'name' =>$request->name,

        ]);

        $notification = array(
            'message' => 'created created Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('roles.all')->with($notification);
    }

    public function EditRole($id)
    {
        $roles = Role::findOrFail($id);
        return view('admin.auth.edit_roles', compact('roles'));

    }

    public function UpdateRole (Request $request)
    {
        $role_id = $request->id;
        role::findOrFail($role_id)->update([
            'name' =>$request->name,
        ]);


        $notification = array(
            'message' => 'Role updated successfully',
            'alert-type' => 'success'
        );


        return redirect()->route('roles.all')->with($notification);
    }

    public function DeleteRole($id)
    {
        Role::findOrFail($id)->delete();


        $notification = array(
            'message' => 'Role deleted successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    /////// Add Role Permisson /////

    public function AllRolesPermission()
    {
        $roles = Role::all();
        return view('admin.auth.all_roles_permission',compact('roles'));

    }


    public function AddRolesPermission()
    {
        $roles = Role::all();
        $permissions = Permission::all();
       $permission_groups = User::getpermissionGroups();
        return view('admin.auth.add_roles_permission',compact('roles','permissions','permission_groups'));
    }

    public function RolePermissionStore(Request $request)
    {
        $data = array();
        $permissions = $request->permission;

        foreach($permissions as $key => $item){
            $data['role_id'] = $request->role_id;
            $data['permission_id'] = $item;

            DB::table('role_has_permissions')->insert($data);

        }

        $notification = array(
            'message' => 'Role permission added successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('role.permission.all')->with($notification);
    }


    public function RolePermissionEdit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        $permission_groups = User::getpermissionGroups();
        return view('admin.auth.edit_roles_permission',compact('role','permissions', 'permission_groups'));

    }

    public function RolePermissionUpdate(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $permissions = $request->permission;

        if (!empty($permissions)){
            $role->syncPermissions($permissions);
        }


        $notification = array(
            'message' => 'Role Permission Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('role.permission.all')->with($notification);

    }

    public function RolePermissionDelete($id)
    {
        $role = Role::findOrFail($id);
        if (!is_null($role)){
            $role->delete();
        }

        $notification = array(
            'message' => 'Role permission deleted successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }






}
