<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use App\RoleUser;
use App\Permission;
use App\PermissionRole;
use App\Services\Hasher;
use Illuminate\Http\Request;
use App\Model\Location\LibOffice;
use App\Model\Location\LibSection;
use App\Http\Controllers\Controller;
use App\Http\Requests\AvatarStoreUpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\Model\Location\LibDepartment;
use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\PasswordResetRequest;
use App\Model\Location\LibOfficeDepartment;
use App\Http\Requests\PasswordChangeRequest;
use App\Model\Location\LibDepartmentSection;
use App\Http\Requests\PermissionStoreRequest;
use App\Http\Requests\PermissionUpdateRequest;
use App\UserLocationTransfer;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->user = new User();
        $this->role = new Role();
        $this->permission = new Permission();
        $this->permission_role = new PermissionRole();
        $this->role_user = new RoleUser();
        $this->lib_office = new LibOffice();
        $this->lib_department = new LibDepartment();
        $this->lib_section = new LibSection();
        $this->lib_office_department = new LibOfficeDepartment();
        $this->lib_department_section = new LibDepartmentSection();
        $this->user_location_transfer = new UserLocationTransfer();
    }

    public function profileView()
    {
        $user = $this->user->getUserDetails(\Auth::id());
        $pending_location_transfer = $this->user_location_transfer->getCurrentPendingTransfer(\Auth::id());
        //dd($pending_location_transfer);
        return view('user-management.profile', compact('user','pending_location_transfer'));
    }
            public function profileEdit($hashid)
            {
                $user = $this->user->getUserDetails(\Auth::id());
                $offices = $this->lib_office->listOfEnabledOffices();
                $departments = $this->lib_office_department->showDepartmentsByOfficeId(\Auth::user()->office_id);
                $sections = $this->lib_department_section->showSectionsByDepartmentId(\Auth::user()->department_id);
                $pending_location_transfer = $this->user_location_transfer->getCurrentPendingTransfer(\Auth::id());
                return view('user-management.profile-edit', compact('user', 'offices', 'departments', 'sections', 'pending_location_transfer'));
            }
            public function profileUpdate(UserUpdateRequest $request)
            {
                $this->user->profileUpdate($request);
                $notification=array(
                    'message'=>'Profile has been updated.',
                    'alert-type'=>'success'
                );
                return Redirect()->route('user.profile.view')->with($notification);
            }
            public function avatarUpdate(AvatarStoreUpdateRequest $request)
            {
                $avatar_name = uniqid()."_".\Auth::user()->username."_".\Auth::user()->last_name; //uniquid + username/idno. + lastname
                $ext = strtolower($request->avatar_new->getClientOriginalExtension());
                $avatar_full_name = $avatar_name.'.'.$ext;
                $saveImageToFolder = $request->file('avatar_new');
                $saveImageToFolder->move(public_path('images/profile/'), $avatar_full_name); //use move since direct saving to public folder
                //save avatar name - profile name
                $this->user->avatarUpdate($avatar_full_name);
                //check if with old
                if ($request->avatar_old != ""){
                    $path = public_path('images/profile/').$request->avatar_old;
                    //delete old image in public folder if exist                    
                    if (File::exists($path)) {//use Illuminate\Support\Facades\File;
                        unlink($path);}
                }

                $notification=array(
                    'message'=>'Image has been updated.',
                    'alert-type'=>'success'
                );
                return Redirect()->back()->with($notification);
            }
            public function changePassword()
            {
                return view('user-management.change-password');
            }
            public function updatePassword(PasswordChangeRequest $request)
            {
                $password = Auth::user()->password;
                if (Hash::check($request->password_old, $password)) {
                    $this->user->resetPassword(\Auth::id(), $request->password);                    
                    \Session::flush();
                    \Auth::logout();
                    $notification=array(
                        'message'=>'Password has been changed.',
                        'alert-type'=>'success'
                    );
                    return Redirect()->route('login')->with($notification);
                }else{
                    return Redirect()->back()->with("error","The current password you have entered is incorrect.");
                }
            }

    public function listUsersForReset() //all users
    {
        abort_unless(\Gate::allows('user_password_reset'), 403);
        //$request->regionId - set to middleware
        $users =  $this->user->listOfUsersByRegionRoleAll(\Auth::user()->office_id, \Gate::allows('su')); // su - if superus      
        return view('user-management.reset-password', compact('users'));
    }
            public function resetUserPassword(PasswordResetRequest $request){
                abort_unless(\Gate::allows('user_password_reset'), 403);
                $this->user->resetPassword($request->user_id, $request->password);    
                $notification=array(
                    'message'=>'Password has been changed.',
                    'alert-type'=>'success'
                );
                return Redirect()->back()->with($notification);
            }       

    public function changeLocationSameOfficeApproval() //all users have change its location
    { 
        abort_unless(\Gate::allows('location_transfer'), 403);
        if (\Gate::allows('su')){
            $transferees = $this->user_location_transfer->getAllCurrentPendingTransferSu(); // su - if superus 
        } else {
            $transferees = $this->user_location_transfer->getAllCurrentPendingTransferSameOffice(\Auth::user()->office_id);
        }
        return view('user-management.user-change-location-sameofficeapproval', compact('transferees'));
    }
    public function changeLocationInitialApproval() //all users have change its location
    { 
        abort_unless(\Gate::allows('location_transfer'), 403);
        if (\Gate::allows('su')){
            $transferees = $this->user_location_transfer->getAllCurrentPendingTransferSu(); // su - if superus 
        } else {
            $transferees = $this->user_location_transfer->getAllCurrentPendingTransferInititalApproval(\Auth::user()->office_id);
        }
        return view('user-management.user-change-location-initialapproval', compact('transferees'));
    }
    public function changeLocationFinalApproval() //all users have change its location
    { 
        abort_unless(\Gate::allows('location_transfer'), 403);
        if (\Gate::allows('su')){
            $transferees = $this->user_location_transfer->getAllCurrentPendingTransferSu(); // su - if superus 
        } else {
            $transferees = $this->user_location_transfer->getAllCurrentPendingTransferFinalApproval(\Auth::user()->office_id);
        }
        return view('user-management.user-change-location-finalapproval', compact('transferees'));
    }
            public function approvedTransfer($hashid){
                abort_unless(\Gate::allows('location_transfer_approval'), 403);
                $trans_loc_id = Hasher::decode($hashid);
                $this->user_location_transfer->approveNewLocation($trans_loc_id);    
                $notification=array(
                    'message'=>'New location has been approved.',
                    'alert-type'=>'success'
                );
                return Redirect()->back()->with($notification);
            }    
            public function disapprovedTransfer($hashid){
                abort_unless(\Gate::allows('location_transfer_approval'), 403);
                $trans_loc_id = Hasher::decode($hashid);
                $this->user_location_transfer->disapproveNewLocation($trans_loc_id);    
                $notification=array(
                    'message'=>'New location has been disapproved.',
                    'alert-type'=>'success'
                );
                return Redirect()->back()->with($notification);
            }  
            
    public function users() //all users
    {
        abort_unless(\Gate::allows('user_view'), 403);
        //$request->regionId - set to middleware
        $users =  $this->user->listOfUsersByRegionRoleAll(\Auth::user()->office_id, \Gate::allows('su')); // su - if superus      
        return view('user-management.users', compact('users'));
    }
            public function validUsers() //all valid users / registered
            {
                abort_unless(\Gate::allows('user_view'), 403);
                //$request->regionId - set to middleware
                $users =  $this->user->listOfValidUsersByRegionRole(\Auth::user()->office_id, \Gate::allows('su'));   
                return view('user-management.users-valid', compact('users'));
            }
            public function registeredUsers() //all newly registered users
            {
                abort_unless(\Gate::allows('user_view'), 403);
                //$request->regionId - set to middleware
                $users =  $this->user->listOfRegisteredUsersByRegionRole(\Auth::user()->office_id, \Gate::allows('su'));   
				return view('user-management.users-registered', compact('users'));
            }
            public function deactivatedUsers() //all deactivated users
            {
                abort_unless(\Gate::allows('user_view'), 403);
                //$request->regionId - set to middleware
                $users =  $this->user->listOfDeactivatedUsersByRegionRole(\Auth::user()->office_id, \Gate::allows('su'));   
                return view('user-management.users-deactivated', compact('users'));
            }
            public function detailsUser($hashid){
                $id = Hasher::decode($hashid);
                $user_details = $this->user->getUserDetails($id);
                //$user_location_details = $this->user_location->getUserLocationDetails($id);
                $enabled_roles = $this->role->listOfEnabledRoles();

                $arr = array('user_details' => $user_details, 
                            //'user_location_details' => $user_location_details, 
                            'enabled_roles' => $enabled_roles);

                return json_encode($arr);
            }
            public function updateUser(Request $request){
                abort_unless(\Gate::allows('user_update'), 403);
                //already validated from blade
                //$withUpdate = $this->user->updateUser($request);
                $withUpdate = $this->role_user->updateRole($request);
                //if ($withUpdate){
                    $notification = array(
                        'message' => 'Successfully updated.',
                        'alert-type' => 'success'
                    );
                // } else {
                //     $notification = array(
                //         'message' => 'No changes have been made.',
                //         'alert-type' => 'info'
                //     );
                // }
                return Redirect()->back()->with($notification);
            }        
            public function activateUser(Request $request){
                abort_unless(\Gate::allows('user_activate'), 403);
                //already validated from blade
                $this->user->activateuser($request);
                $notification=array(
                    'message'=>'User has been activated.',
                    'alert-type'=>'success'
                );
                return Redirect()->back()->with($notification);
            }
            public function disableUser($hashid){
                abort_unless(\Gate::allows('user_disable'), 403);
                $id = Hasher::decode($hashid);
                $this->user->disableUser($id);
                $notification=array(
                    'message'=>'User has been disabled.',
                    'alert-type'=>'success'
                );
                return Redirect()->back()->with($notification);
            }        
            public function enableUser($hashid){
                abort_unless(\Gate::allows('user_enable'), 403);
                $id = Hasher::decode($hashid);
                $this->user->enableUser($id);
                $notification=array(
                    'message'=>'User has been enabled.',
                    'alert-type'=>'success'
                );
                return Redirect()->back()->with($notification);
            }
            
    public function roles()
    {
        abort_unless(\Gate::allows('role_view'), 403);
        $roles =  $this->role->listOfRoles(\Gate::allows('su'));// su - if superus
        return view('user-management.roles', compact('roles'));
    }
            public function createRole(){
                $permissions =  $this->permission->listOfEnabledPermissions();
                return json_encode($permissions);
            }
            public function storeRole(RoleStoreRequest $request)
            {
                abort_unless(\Gate::allows('role_store'), 403);
                //save role first
                $roleId = $this->role->storeRoleReturnId($request);
                //then all assigned permissions
                $this->permission_role->storePermissionRole($roleId, $request);

                $notification = array(
                    'message' => 'Successfully saved.',
                    'alert-type' => 'success'
                );

                return Redirect()->back()->with($notification);
            }
            public function editRole($hid)
            {
                $id = Hasher::decode($hid);
                $role = $this->role->searchRoleById($id);
                $permissions = $this->permission->listOfEnabledPermissions(); // all permissions
                $selected_permissions = $this->permission_role->showPermissionsByRoleId($id);

                $arr = array('role' => $role, 
                            'permissions' => $permissions, 
                            'selected_permissions' => $selected_permissions);

                return json_encode($arr);
            }
            public function updateRole(RoleUpdateRequest $request)
            {
                abort_unless(\Gate::allows('role_update'), 403);
                $id = Hasher::decode($request->hashid);
                //update role details
                $withUpdate = $this->role->updateRole($id, $request);
                //delete all permission from role first
                $this->permission_role->deletePermissionRoleByRoleId($id);
                //then store all selected permission
                $this->permission_role->storePermissionRole($id, $request);

                //if ($withUpdate){
                    $notification = array(
                        'message' => 'Successfully updated.',
                        'alert-type' => 'success'
                    );
                // } else {
                //     $notification = array(
                //         'message' => 'No changes have been made.',
                //         'alert-type' => 'info'
                //     );
                // }
                
                return Redirect()->back()->with($notification);
            }
            public function disableRole($hashid){
                abort_unless(\Gate::allows('role_disable'), 403);
                $id = Hasher::decode($hashid);
                $this->role->disableRole($id);
                $notification=array(
                    'message'=>'Role has been disabled.',
                    'alert-type'=>'success'
                );
                return Redirect()->back()->with($notification);
            }        
            public function enableRole($hashid){
                abort_unless(\Gate::allows('role_enable'), 403);
                $id = Hasher::decode($hashid);
                $this->role->enableRole($id);
                $notification=array(
                    'message'=>'Role has been enabled.',
                    'alert-type'=>'success'
                );
                return Redirect()->back()->with($notification);
            }
    public function permissions()
    {
        abort_unless(\Gate::allows('permission_view'), 403);
        $permissions =  $this->permission->listOfPermissions(\Gate::allows('su'));
        return view('user-management.permissions', compact('permissions'));
    }
            public function storePermission(PermissionStoreRequest $request)
            {
                abort_unless(\Gate::allows('permission_store'), 403);
                $this->permission->storePermission($request);
                $notification = array(
                    'message' => 'Successfully saved.',
                    'alert-type' => 'success'
                );

                return Redirect()->back()->with($notification);
            }
            public function editPermission($hashid)
            {
                $id = Hasher::decode($hashid);
                $full_details =  $this->permission->searchPermissionById($id);
                return json_encode($full_details);
            }
            public function updatePermission(PermissionUpdateRequest $request)
            {
                abort_unless(\Gate::allows('permission_update'), 403);
                $id = Hasher::decode($request->hashid);
                $withUpdate = $this->permission->updatePermission($id, $request);
                if ($withUpdate){
                    $notification = array(
                        'message' => 'Successfully updated.',
                        'alert-type' => 'success'
                    );
                } else {
                    $notification = array(
                        'message' => 'No changes have been made.',
                        'alert-type' => 'info'
                    );
                }

                return Redirect()->back()->with($notification);
            }
            public function disablePermission($hashid){
                abort_unless(\Gate::allows('permission_disable'), 403);
                $id = Hasher::decode($hashid);
                $this->permission->disablePermission($id);
                $notification=array(
                    'message'=>'Permission has been disabled.',
                    'alert-type'=>'success'
                );
                return Redirect()->back()->with($notification);
            }        
            public function enablePermission($hashid){
                abort_unless(\Gate::allows('permission_enable'), 403);
                $id = Hasher::decode($hashid);
                $this->permission->enablePermission($id);
                $notification=array(
                    'message'=>'Permission has been enabled.',
                    'alert-type'=>'success'
                );
                return Redirect()->back()->with($notification);
            }
            public function showPermissionsByRoleId($hashroleid){
                $roleId = Hasher::decode($hashroleid);
                $permissions =  $this->permission_role->showPermissionsByRoleId($roleId);
                return json_encode($permissions);
            }
}
