<?php

namespace App\Model\Location;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LibDepartment extends Model
{
    use SoftDeletes;
    
    public function listOfDepartments() //softdeleted included, location management controller. departments function
    {
        return DB::table('lib_departments')
            ->orderBy('remarks', 'ASC')
            ->orderBy('title', 'ASC')
            ->get();
    }

    //softdeleted not included, location management controller. createOffice function
    public function listOfEnabledDepartments() 
    {
        return DB::table('lib_departments')
            ->where('deleted_at', null)
            ->orderBy('remarks', 'ASC')
            ->orderBy('title', 'ASC')
            ->get();
    }

    public function searchDepartmentById($id) //location management controller, HomeController. editDepartment, about function
    {
        return DB::table('lib_departments')->where('id', $id)->get();
    }

    public function storeDepartmentReturnId($request) //location management controller, storeDepartment function
    {
        $data = array(
            'title' => $request['title'],
            'remarks' => $request['remarks'],
            'created_at' => Carbon::now()
        );

        return DB::table('lib_departments')->insertGetId($data);
    }

    public function updateDepartment($id, $request) //location management controller, updateDepartment function
    {
        $department = LibDepartment::where('id', '=', $id)->first(); //softdeleted will be not found
        if ($department === null){ //if not exist or deleted
            return false;
        } 

        $department->title = $request->title;
        $department->remarks = $request->remarks;
        $withUpdate = $department->isDirty();
        $department->save();

        if ($withUpdate) { return true; }
        else { return false; }
    }

    public function disableDepartment($id) //location management controller, disableDepartment function
    {
        LibDepartment::find($id)->delete();
    }

    public function enableDepartment($id) //location management controller, enableDepartment function
    {
        LibDepartment::withTrashed()->where('id', $id)->restore();
    }
}
