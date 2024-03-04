<?php

namespace App;

use App\UserLocationTransfer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, SoftDeletes;

    // public function __construct()
    // {
            // If you enable this, error will occured:
            // SQLSTATE[42S22]: Column not found: 1054 Unknown column 'user_location_transfer' in 'where clause' (SQL: insert user_location_transfer, 
    //     $this->user_location_transfer = new UserLocationTransfer(); 
    // }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'avatar',
        'username',
        'last_name',
        'first_name',
        'middle_name',
        'suffix_name',
        'email',
        'office_id',
        'department_id',
        'section_id',
        'password',
        'activated',
        'created_at',
        'updated_at',
        'deleted_at',
        'remember_token',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = [
        'updated_at',
        'created_at',
        'deleted_at',
        'email_verified_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    //C:\xampp\htdocs\(Laravel)Document Tracking System\dts\vendor\laravel\ui\auth-backend\RegistersUsers.php  registered function
    public function GetAllUserWithActivateUserPermission($officeId, $permissionId){ //$permissionId = 3 = user_activate
        // filter office_id at users based on $request->office_id 
        //     select user ids then link to role_user by user_id
        //         get role_id then link to permission_role by role_id
        //             get permission_id = 3 = //user_activate
        return DB::table('users as u')
            ->leftJoin('role_user as ru', 'u.id', '=', 'ru.user_id')
            ->leftJoin('permission_role as pr', 'ru.role_id', '=', 'pr.role_id')
            ->select('u.email', 'u.last_name', 'u.first_name', 'u.middle_name', 'u.suffix_name')
            ->where('u.office_id', $officeId) 
            ->where('pr.permission_id', $permissionId)
            //->orderBy('u.created_at', 'DESC')
            ->get();
    }

    //DocumentController create, editDraft function
    //RouteController  create, editDraft function
    public function listOfAllEnabledUsers() //NOT YET USED
    {
        return DB::table('users')
            ->select('users.*')
            ->where('users.deleted_at', null)
            ->orderBy('users.last_name', 'ASC')
            ->get();
    }

    //softdeleted not included, DocumentController create, editDraft function
    //RouteController  create, editDraft function
    // public function listOfAllEnabledUsersExceptitself() 
    // {
    //     return DB::table('users')
    //         ->select('users.*')
    //         ->where('users.deleted_at', null)
    //         ->where('users.id', '!=', Auth::user()->id) //except user itself
    //         ->orderBy('users.last_name', 'ASC')
    //         ->get();
    // }

    public function listOfUsersByRegionRoleAll($regionCode, $isSu) //softdeleted included, user management controller. users, listUsersForReset function
    {
        //if su, select all regardless of location and role, else trim per location and except su
        if ($isSu){ //all location, all users
            return DB::table('users')
            ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
            ->select('users.*', 'roles.title as role_title', 'roles.id as role_id')
            ->orderBy('users.created_at', 'DESC')
            ->get();
        } else { //except superuser and per respective location
            return DB::table('users')
            ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
            ->select('users.*', 'roles.title as role_title', 'roles.id as role_id')
            ->where('role_user.role_id', '!=', 1) //except su
            ->where('users.office_id', $regionCode)
            ->orderBy('users.created_at', 'DESC')
            ->get();
        }
    }
            public function listOfValidUsersByRegionRole($regionCode, $isSu) //softdeleted included, user management controller. validUsers function
            {
                //if su, select all regardless of location and role, else trim per location and except su
                if ($isSu){ //all location, all valid users / registered
                    return DB::table('users')
                    ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
                    ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
                    ->where('users.activated', 1)//activated
                    ->where('users.deleted_at', null)//not softdeleted
                    ->select('users.*', 'roles.title as role_title', 'roles.id as role_id')
                    ->orderBy('users.created_at', 'DESC')
                    ->get();
                } else { //except superuser and per respective location
                    return DB::table('users')
                    ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
                    ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
                    ->select('users.*', 'roles.title as role_title', 'roles.id as role_id')
                    ->where('users.activated', 1)//activated
                    ->where('users.deleted_at', null)//not softdeleted
                    ->where('role_user.role_id', '!=', 1) //except su
                    ->where('users.office_id', $regionCode)
                    ->orderBy('users.created_at', 'DESC')
                    ->get();
                }
            }
            public function listOfRegisteredUsersByRegionRole($regionCode, $isSu) //softdeleted included, user management controller. registeredUsers function
            {
                //if su, select all regardless of location and role, else trim per location and except su
                if ($isSu){ //all location, all newly registered users
                    return DB::table('users')
                    ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
                    ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
                    ->where('users.activated', 0)//not yet activated since registered
                    ->select('users.*', 'roles.title as role_title', 'roles.id as role_id')
                    ->orderBy('users.created_at', 'DESC')
                    ->get();
                } else { //except superuser and per respective location
                    return DB::table('users')
                    ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
                    ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
                    ->select('users.*', 'roles.title as role_title', 'roles.id as role_id')
                    ->where('users.activated', 0)//not yet activated since registered
                    //->where('role_user.role_id', '!=', 1) //except su
                    ->where('users.office_id', $regionCode)
                    ->orderBy('users.created_at', 'DESC')
                    ->get();
                }
            }
            public function listOfDeactivatedUsersByRegionRole($regionCode, $isSu) //softdeleted included, user management controller. deactivatedUsers function
            {
                //if su, select all regardless of location and role, else trim per location and except su
                if ($isSu){ //all location, all deactivated users
                    return DB::table('users')
                    ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
                    ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
                    ->whereNotNull('users.deleted_at')//softdeleted
                    ->select('users.*', 'roles.title as role_title', 'roles.id as role_id')
                    ->orderBy('users.created_at', 'DESC')
                    ->get();
                } else { //except superuser and per respective location
                    return DB::table('users')
                    ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
                    ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
                    ->select('users.*', 'roles.title as role_title', 'roles.id as role_id')
                    ->whereNotNull('users.deleted_at')//softdeleted
                    ->where('role_user.role_id', '!=', 1) //except su
                    ->where('users.office_id', $regionCode)
                    ->orderBy('users.created_at', 'DESC')
                    ->get();
                }
            }

    // public function updateUser($request) //UserController. updateUser function
    // {
    //     $user = User::find($request->user_id_e); //softdeleted will be not found
    //     if ($user === null){ //if not exist or deleted
    //         return false;
    //     }

    //     $user->role_id = $request->role_e;
    //     $withUpdate = $user->isDirty();
    //     $user->save();

    //     if ($withUpdate) { return true; }
    //     else { return false; }
    // }

    public function activateuser($request) //UserController, activateUser function
    {
        //activate at Users table
        //$user = User::find($request->user_id); //not applicable if softdeleted
        DB::table('users')
            ->where('id', $request->user_id)
            ->update(['activated'=> 1]);

        //Add role at user_role table
        $roleData =array(
            'user_id' => $request->user_id,
            'role_id' => $request->role
        );
        DB::table('role_user')->insert($roleData);
    }

    public function profileUpdate($request) //UserController, profileUpdate function
    {
        // DB::table('users')
        //     ->where('id', $request->user_id)
        //     ->update(['activated'=> 1]);
        $data = User::find($request->user_id);
        //current locaion
        $c_office = $data->office_id;
        $c_department = $data->department_id;
        $c_section = $data->section_id;

        $data->username = $request->username;
        $data->email = $request->email;
        $data->last_name = $request->last_name;
        $data->first_name = $request->first_name;
        $data->middle_name = $request->middle_name;
        $data->suffix_name = $request->suffix_name;

            //need this, to use the isDirty function and check if there are changes
            $data->office_id = $request->office;
            $data->department_id = $request->department;
            $data->section_id = $request->section;
        
        //save to transfer location if there are changes in office, department or in section
        if($data->isDirty('office_id') || $data->isDirty('department_id') || $data->isDirty('section_id')){
            $this->user_location_transfer = new UserLocationTransfer();
            $this->user_location_transfer->changeUserLocationByUserId($request);
        } 
        
            //user change of location should not be updated in users table until the admin approved it.
            //copy the current location again, to bypass above $request
            $data->office_id = $c_office;
            $data->department_id = $c_department;
            $data->section_id = $c_section;

        $data->save();
        
    }

    public function avatarUpdate($avatar_full_name) //UserController, avatarUpdate function
    {
        $data = User::find(\Auth::id());
        $data->avatar = $avatar_full_name;
        //$withUpdate = $data->isDirty();
        $data->save();
    }

    public function getUserDetails($id) //softdeleted included, UserController. detailsUser, profileView, profileEdit function
    {
        //return User::find($id);
        return DB::table('users')
            ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id') //added for profileView          
            ->leftJoin('lib_offices', 'users.office_id', '=', 'lib_offices.id')
            ->leftJoin('lib_departments', 'users.department_id', '=', 'lib_departments.id')
            ->leftJoin('lib_sections', 'users.section_id', '=', 'lib_sections.id')
            ->select('users.*', 'roles.title as role_title', 'role_user.role_id', 'lib_offices.title as office_title', 'lib_departments.title as department_title', 'lib_sections.title as section_title')
            ->where('users.id', $id)
            ->first();
    }

    public function getUserLimitedDetails($id) //softdeleted excluded, DocumentController store function
    {
        return User::find($id);
    }

    public function resetPassword($userid, $newPassword) //user management controller, updatePassword, resetUserPassword function
    {
        $user = User::find($userid);
        $user->password = Hash::make($newPassword);
        $user->save();
    }

    public function disableUser($id) //user management controller, disableOffice function
    {
        User::find($id)->delete();
    }

    public function enableUser($id) //user management controller, enableOffice function
    {
        //User::withTrashed()->where('id', $id)->restore();
        User::find($id)->restore();
    }
}
