<?php

namespace App\Model\Location;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class LibOfficeDepartment extends Model
{
    public function storeDepartmentOffice($officeId, $request) //location management controller. storeOffice & updateOffice function
    {
        //save if has selected
        if ($request->input("chkbx_offices") != null){
            $details = array();
            foreach ($request->input("chkbx_offices") as $departmentId){ //this only save checked items
                $details['office_id'] = $officeId;
                $details['department_id'] = $departmentId;
                DB::table('lib_office_departments')->insert($details);            
            }
        }    
    }

    //location management $ RegisterController, UserController controller. showDepartmentsByOfficeId, editOffice, profileEdit function
    //PublishController editPublishedDoc function
    public function showDepartmentsByOfficeId($officeId) 
    {
        return DB::table('lib_office_departments')
            ->join('lib_departments', 'lib_office_departments.department_id', 'lib_departments.id')
            ->select('lib_departments.*')
            ->where('lib_office_departments.office_id', $officeId)
            ->where('lib_departments.deleted_at', null) //not disabled
            ->orderBy('lib_departments.remarks', 'ASC')
            ->orderBy('lib_departments.title', 'ASC')
            ->get();
    }   
    
    public function deleteDepartmentOfficeByOfficeId($officeId) //location management controller. updateOffice function
    {
        DB::table('lib_office_departments')->where('office_id', $officeId)->delete();
    }
}
