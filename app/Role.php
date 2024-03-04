<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
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

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function listOfRoles($isSu) //UserController. roles function
    {
        if ($isSu){
            return DB::table('roles')
                    ->orderBy('description', 'ASC')
                    ->get();
        } else {
            return DB::table('roles')
                    ->where('roles.id', '!=', 1) //su
                    ->orderBy('description', 'ASC')
                    ->get();
        }
        
    }

    public function listOfEnabledRoles() //UserController. detailsUser function
    {
        return DB::table('roles')
            ->where('deleted_at', null)
            ->orderBy('description', 'ASC')
            ->get();
    }

    public function searchRoleById($id) //user management controller
    {
        return DB::table('roles')->where('id', $id)->get();
    }

    public function searchRoleByUserId($id) //HomeController. about function
    {
        return DB::table('roles')
                ->leftJoin('role_user', 'roles.id', 'role_user.role_id')
                ->where('role_user.user_id', $id)
                ->get();
    }

    public function storeRoleReturnId($request) //user management controller, storeRole function
    {
        // $role = new Role();
        // $role->title = $request->title;
        // $role->slug = Str::slug($request->title, '_');
        // $role->description = $request->description;
        // $role->save();
        // return $role->id; //rreturn last inserted id NOT YET TRIED
        //OR
        $data = array(
            'title' => $request['title'],
            'slug' => Str::slug($request['title'], '_'),
            'description' => $request['description'],
            'created_at' => Carbon::now()
        );

        return DB::table('roles')->insertGetId($data);
    }

    public function updateRole($id, $request) //user management controller
    {
        $role = Role::where('id', '=', $id)->first(); //softdeleted will be not found
        if ($role === null){ //if not exist or deleted
            return false;
        } 


        $role->title = $request->title;
        $role->description = $request->description;
        $withUpdate = $role->isDirty();
        $role->save();

        if ($withUpdate) { return true; }
        else { return false; }
    }

    public function disableRole($id) //user management controller
    {
        Role::find($id)->delete();
    }

    public function enableRole($id) //user management controller
    {
        Role::withTrashed()->where('id', $id)->restore();
    }

}
