<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'title',
        'slug',
        'description',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function listOfPermissions($isSu) //user management controller. permissions function
    {
        if ($isSu) {
            return DB::table('permissions')
            ->orderBy('description', 'ASC')
            ->orderBy('title', 'ASC')
            ->get();
        } else {
            return DB::table('permissions')
            ->whereNotIn('permissions.slug',
                                ['role_store',
                                'role_update',
                                'role_disable',
                                'role_enable',
                                'permission_store',
                                'permission_update',
                                'permission_disable',
                                'permission_enable',
                                'office_store',
                                'office_update',
                                'office_disable',
                                'office_enable',
                                'department_store',
                                'department_update',
                                'department_disable',
                                'department_enable',
                                'section_store',
                                'section_update',
                                'section_disable',
                                'section_enable'])
            ->orderBy('description', 'ASC')
            ->orderBy('title', 'ASC')
            ->get();
        }
        
    }

    public function listOfEnabledPermissions() //user management controller, roles.blade UserController, editRole function, except softdeleted
    {
        return DB::table('permissions')
            ->where('deleted_at', null)
            ->orderBy('description', 'ASC')
            ->orderBy('title', 'ASC')
            ->get();
    }

    public function searchPermissionById($id) //user management controller
    {
        return DB::table('permissions')->where('id', $id)->get();
    }

    public function storePermission($request) //user management controller
    {
        $permission = new Permission();
        $permission->title = $request->title;
        $permission->slug = Str::slug($request->title, '_');
        $permission->description = $request->description;
        $permission->save();
    }

    public function updatePermission($id, $request) //user management controller
    {
        $permission = Permission::findOrFail($id);
        $permission->title = $request->title;
        $permission->description = $request->description;
        $withUpdate = $permission->isDirty();
        $permission->save();

        if ($withUpdate) { return true; }
        else { return false; }
    }

    public function disablePermission($id) //user management controller
    {
        Permission::find($id)->delete();
    }

    public function enablePermission($id) //user management controller
    {
        Permission::withTrashed()->where('id', $id)->restore();
    }
}
