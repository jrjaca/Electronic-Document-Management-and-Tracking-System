<?php

namespace App;

use App\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class UserLocationTransfer extends Model
{
    // public function __construct()
    // {
    //     //ini_set('memory_limit', -1);
    //     //$this->user = new User(); //causing high memory utilization
    // }

    // protected $fillable = [
    //     'user_id',
    //     'user_id_admin_from',
    //     'action_date_from',
    //     'office_id_from',
    //     'department_id_from',
    //     'section_id_from',
    //     'user_id_admin_to',
    //     'action_date_to',
    //     'office_id_to',
    //     'department_id_to',
    //     'section_id_to',
    //     'approved_transfer',
    //     'created_at',
    //     'updated_at',
    // ];

    public function changeUserLocationByUserId($request) //UserModel, profileUpdate function
    {
        $data = new UserLocationTransfer();

        $data->user_id = $request->user_id;
        //----FROM - Current----//
        $user = User::find($request->user_id); //get current location
        $data->office_id_from = $user->office_id;
        $data->department_id_from = $user->department_id;
        $data->section_id_from = $user->section_id;
        //----TO----//
        $data->office_id_to = $request->office;
        $data->department_id_to = $request->department;
        $data->section_id_to = $request->section;

        $data->save();        
    }

    public function getCurrentPendingTransfer($user_id) //UserController, profileView function
    {
        return DB::table('user_location_transfers')
            //get description of transfer location only and other info from user_location_transfers table
            ->leftJoin('lib_offices', 'user_location_transfers.office_id_to', 'lib_offices.id')
            ->leftJoin('lib_departments', 'user_location_transfers.department_id_to', 'lib_departments.id')
            ->leftJoin('lib_sections', 'user_location_transfers.section_id_to', 'lib_sections.id')
            ->where('user_location_transfers.user_id', $user_id)
            ->where('user_location_transfers.approved_transfer', null)
            ->select('user_location_transfers.*', 'lib_offices.title as office_title', 'lib_departments.title as department_title', 'lib_sections.title as section_title')
            ->orderBy('user_location_transfers.created_at', 'DESC') //last update
            ->first();
    }

    public function getAllCurrentPendingTransferSu() //UserController. changeLocationSameOfficeApproval, changeLocationInitialApproval, changeLocationFinalApproval function
    {
        return DB::table('user_location_transfers')
        ->leftJoin('users', 'user_location_transfers.user_id', '=', 'users.id')
        ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
        ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
        
        ->leftJoin('lib_offices as offices_from', 'user_location_transfers.office_id_from', 'offices_from.id')
        ->leftJoin('lib_departments as departments_from', 'user_location_transfers.department_id_from', 'departments_from.id')
        ->leftJoin('lib_sections as sections_from', 'user_location_transfers.section_id_from', 'sections_from.id')

        ->leftJoin('lib_offices as offices_to', 'user_location_transfers.office_id_to', 'offices_to.id')
        ->leftJoin('lib_departments as departments_to', 'user_location_transfers.department_id_to', 'departments_to.id')
        ->leftJoin('lib_sections as sections_to', 'user_location_transfers.section_id_to', 'sections_to.id')
        
        ->where('users.deleted_at', null)//not softdeleted
        ->where('user_location_transfers.approved_transfer', null) //pending for approval
        ->select('user_location_transfers.*',
                'users.avatar as avatar',
                'users.username as username',
                'users.last_name as last_name',
                'users.first_name as first_name',
                'users.middle_name as middle_name',
                'users.suffix_name as suffix_name',
                'roles.title as role_title', 
                'roles.id as role_id',

                'offices_from.title as office_from',
                'departments_from.title as department_from',
                'sections_from.title as section_from',

                'offices_to.title as office_to',
                'departments_to.title as department_to',
                'sections_to.title as section_to')

        ->orderBy('user_location_transfers.created_at', 'DESC') 
        ->get();
    }

    public function getAllCurrentPendingTransferSameOffice($regionCode) //UserController. changeLocationSameOfficeApproval function
    {
        return DB::table('user_location_transfers')
            ->leftJoin('users', 'user_location_transfers.user_id', '=', 'users.id')
            ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
            
            ->leftJoin('lib_offices as offices_from', 'user_location_transfers.office_id_from', 'offices_from.id')
            ->leftJoin('lib_departments as departments_from', 'user_location_transfers.department_id_from', 'departments_from.id')
            ->leftJoin('lib_sections as sections_from', 'user_location_transfers.section_id_from', 'sections_from.id')

            ->leftJoin('lib_offices as offices_to', 'user_location_transfers.office_id_to', 'offices_to.id')
            ->leftJoin('lib_departments as departments_to', 'user_location_transfers.department_id_to', 'departments_to.id')
            ->leftJoin('lib_sections as sections_to', 'user_location_transfers.section_id_to', 'sections_to.id')
            
            ->where('users.deleted_at', null)//not softdeleted
            ->where('user_location_transfers.approved_transfer', null) //pending for approval
            ->where('role_user.role_id', '!=', 1) //except su
            ->where('offices_from.id', $regionCode) //same office approval FROM
            ->where('offices_to.id', $regionCode) //same office approval TO
            ->select('user_location_transfers.*',
                    'users.avatar as avatar',
                    'users.username as username',
                    'users.last_name as last_name',
                    'users.first_name as first_name',
                    'users.middle_name as middle_name',
                    'users.suffix_name as suffix_name',
                    'roles.title as role_title', 
                    'roles.id as role_id',

                    'offices_from.title as office_from',
                    'departments_from.title as department_from',
                    'sections_from.title as section_from',

                    'offices_to.title as office_to',
                    'departments_to.title as department_to',
                    'sections_to.title as section_to')

            ->orderBy('user_location_transfers.created_at', 'DESC') 
            ->get();
    }

    public function getAllCurrentPendingTransferInititalApproval($regionCode) //UserController. changeLocationInitialApproval function
    {
        return DB::table('user_location_transfers')
            ->leftJoin('users', 'user_location_transfers.user_id', '=', 'users.id')
            ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
            
            ->leftJoin('lib_offices as offices_from', 'user_location_transfers.office_id_from', 'offices_from.id')
            ->leftJoin('lib_departments as departments_from', 'user_location_transfers.department_id_from', 'departments_from.id')
            ->leftJoin('lib_sections as sections_from', 'user_location_transfers.section_id_from', 'sections_from.id')

            ->leftJoin('lib_offices as offices_to', 'user_location_transfers.office_id_to', 'offices_to.id')
            ->leftJoin('lib_departments as departments_to', 'user_location_transfers.department_id_to', 'departments_to.id')
            ->leftJoin('lib_sections as sections_to', 'user_location_transfers.section_id_to', 'sections_to.id')
            
            ->where('users.deleted_at', null)//not softdeleted
            ->where('user_location_transfers.approved_transfer', null) //pending for approval
            ->where('role_user.role_id', '!=', 1) //except su
            ->where('offices_from.id', $regionCode) //From your office
            ->where('offices_to.id', '!=', $regionCode) //To different office
            ->where('user_location_transfers.user_id_admin_from', null) //Not yet approved initially
            ->select('user_location_transfers.*',
                    'users.avatar as avatar',
                    'users.username as username',
                    'users.last_name as last_name',
                    'users.first_name as first_name',
                    'users.middle_name as middle_name',
                    'users.suffix_name as suffix_name',
                    'roles.title as role_title', 
                    'roles.id as role_id',

                    'offices_from.title as office_from',
                    'departments_from.title as department_from',
                    'sections_from.title as section_from',

                    'offices_to.title as office_to',
                    'departments_to.title as department_to',
                    'sections_to.title as section_to')

            ->orderBy('user_location_transfers.created_at', 'DESC') 
            ->get();
    }

    public function getAllCurrentPendingTransferFinalApproval($regionCode) //UserController. changeLocationFinalApproval function
    {
        return DB::table('user_location_transfers')
            ->leftJoin('users', 'user_location_transfers.user_id', '=', 'users.id')
            ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
            
            ->leftJoin('lib_offices as offices_from', 'user_location_transfers.office_id_from', 'offices_from.id')
            ->leftJoin('lib_departments as departments_from', 'user_location_transfers.department_id_from', 'departments_from.id')
            ->leftJoin('lib_sections as sections_from', 'user_location_transfers.section_id_from', 'sections_from.id')

            ->leftJoin('lib_offices as offices_to', 'user_location_transfers.office_id_to', 'offices_to.id')
            ->leftJoin('lib_departments as departments_to', 'user_location_transfers.department_id_to', 'departments_to.id')
            ->leftJoin('lib_sections as sections_to', 'user_location_transfers.section_id_to', 'sections_to.id')
            
            ->where('users.deleted_at', null)//not softdeleted
            ->where('user_location_transfers.approved_transfer', null) //pending for approval
            ->where('role_user.role_id', '!=', 1) //except su
            ->where('offices_from.id', '!=', $regionCode) //From different office
            ->where('offices_to.id', $regionCode) //To your office
            ->whereNotNull('user_location_transfers.user_id_admin_from') //It should be approved initially from original location
            ->select('user_location_transfers.*',
                    'users.avatar as avatar',
                    'users.username as username',
                    'users.last_name as last_name',
                    'users.first_name as first_name',
                    'users.middle_name as middle_name',
                    'users.suffix_name as suffix_name',
                    'roles.title as role_title', 
                    'roles.id as role_id',

                    'offices_from.title as office_from',
                    'departments_from.title as department_from',
                    'sections_from.title as section_from',

                    'offices_to.title as office_to',
                    'departments_to.title as department_to',
                    'sections_to.title as section_to')

            ->orderBy('user_location_transfers.created_at', 'DESC') 
            ->get();
    }

    public function approveNewLocation($trans_loc_id){ //UserController. approvedTransfer function
        $data = UserLocationTransfer::find($trans_loc_id);

        //updating users table upon final approval
        $data_arr = array(
            'user_id' => $data->user_id,
            'office_id_new' => $data->office_id_to,
            'department_id_new' => $data->department_id_to,
            'section_id_new' => $data->section_id_to
        );

        if (\Gate::allows('su')){ //SU
            if ($data->user_id_admin_from === null){
                $data->user_id_admin_from = \Auth::id();
                $data->action_date_from = Carbon::now();
            }            
            $data->user_id_admin_to = \Auth::id();
            $data->action_date_to = Carbon::now();
            $data->approved_transfer = "Y";
            $this->updateUserLocation($data_arr);
        } else {
            if ($data->office_id_from == $data->office_id_to){ //same office approval, but to be transfer in different department or section
                $data->user_id_admin_from = \Auth::id();
                $data->user_id_admin_to = \Auth::id();
                $data->approved_transfer = "Y";
                $data->action_date_from = Carbon::now();
                $data->action_date_to = Carbon::now();
                $this->updateUserLocation($data_arr);
            } else {
                //Initial approval
                if ($data->office_id_from == \Auth::user()->office_id){ //before leaving to original office
                    $data->user_id_admin_from = \Auth::id();
                    $data->action_date_from = Carbon::now();
                } 
                
                //Final Approval
                if ($data->office_id_to == \Auth::user()->office_id){//admitting to new office
                    $data->approved_transfer = "Y"; //admin TO has a final approval
                    $data->user_id_admin_to = \Auth::id();
                    $data->action_date_to = Carbon::now();
                    $this->updateUserLocation($data_arr);
                }
            }
        }

        $data->save();      
    }

    public function disapproveNewLocation($trans_loc_id){ //UserController. disapprovedTransfer function
        $data = UserLocationTransfer::find($trans_loc_id);
        if (\Gate::allows('su')){ //SU
            if ($data->user_id_admin_from === null){
                $data->user_id_admin_from = \Auth::id();
                $data->action_date_from = Carbon::now();
            }   
            $data->user_id_admin_to = \Auth::id();
            $data->action_date_to = Carbon::now();
        } else {
            if ($data->office_id_from == $data->office_id_to){ //same office approval, but to be transfer in different department or section
                $data->user_id_admin_from = \Auth::id();
                $data->action_date_from = Carbon::now();
            } else {
                $data->user_id_admin_to = \Auth::id();
                $data->action_date_to = Carbon::now();
            }
        }

        $data->approved_transfer = "N"; //admin TO has a final approval
        $data->save(); 
    }

    //users table
    public function updateUserLocation($data_arr) //UserLocationTransfer Model, approveNewLocation function
    {
        $data = User::find($data_arr['user_id']);
        $data->office_id = $data_arr['office_id_new'];
        $data->department_id = $data_arr['department_id_new'];
        $data->section_id = $data_arr['section_id_new'];
        $data->save();
    }

}