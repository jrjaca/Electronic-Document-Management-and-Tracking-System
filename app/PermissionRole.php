<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PermissionRole extends Model
{
    public function storePermissionRole($roleId, $request) //user management controller, storeRole & updateRole function
    {
        //save if has selected
        if ($request->input("chkbx_roles") != null){
            $details = array();
            foreach ($request->input("chkbx_roles") as $permissionId){ //this only save checked items
                // $prole = new PermissionRole(); // permission_roles as database cannot recognized by PermissionRole because I change the name of DB manually to permission_role
                // $prole->role_id = $roleId;
                // $prole->permission_id = $permissionId;
                // $prole->save();

                $details['role_id'] = $roleId;
                $details['permission_id'] = $permissionId;
                DB::table('permission_role')->insert($details);            
            }
        }    
    }

    public function showPermissionsByRoleId($roleId) //user management controller, showPermissionsByRoleId and editRole function
    {
        return DB::table('permission_role')
            ->join('permissions', 'permission_role.permission_id', 'permissions.id')
            ->select('permissions.*')
            ->where('permission_role.role_id', $roleId)
            ->where('permissions.deleted_at', null) //not disabled
            ->orderBy('permissions.title', 'ASC')
            ->get();
    }   
    
    public function deletePermissionRoleByRoleId($roleId) //user management controller, updateRole function
    {
        DB::table('permission_role')->where('role_id', $roleId)->delete();
    }
    
}
