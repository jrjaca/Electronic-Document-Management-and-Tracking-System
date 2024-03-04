<?php

namespace App\Model\Location;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class LibDepartmentSection extends Model
{
    public function storeSectionDepartment($departmentId, $request) //location management controller. storeDepartment & updateDepartment function
    {
        //save if has selected
        if ($request->input("chkbx_departments") != null){
            $details = array();
            foreach ($request->input("chkbx_departments") as $sectionId){ //this only save checked items
                $details['department_id'] = $departmentId;
                $details['section_id'] = $sectionId;
                DB::table('lib_department_sections')->insert($details);            
            }
        }    
    }

    public function showSectionsByDepartmentId($departmentId) //location management & RegisterController, UserController. showSectionsByDepartmentId, editDepartment, profileEdit function
    {
        return DB::table('lib_department_sections')
            ->join('lib_sections', 'lib_department_sections.section_id', 'lib_sections.id')
            ->select('lib_sections.*')
            ->where('lib_department_sections.department_id', $departmentId)
            ->where('lib_sections.deleted_at', null) //not disabled
            ->orderBy('lib_sections.remarks', 'ASC')
            ->orderBy('lib_sections.title', 'ASC')
            ->get();
    }   
    
    public function deleteSectionDepartmentByDeptId($departmentId) //location management controller. updateDepartment function
    {
        DB::table('lib_department_sections')->where('department_id', $departmentId)->delete();
    }
}
