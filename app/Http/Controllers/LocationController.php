<?php

namespace App\Http\Controllers;

use App\Services\Hasher;
use Illuminate\Support\Str;
use Illuminate\Auth\Access\Gate;
use App\Model\Location\LibOffice;
use App\Model\Location\LibSection;
use App\Model\Location\LibDepartment;
use App\Http\Requests\OfficeStoreRequest;
use App\Http\Requests\OfficeUpdateRequest;
use App\Http\Requests\SectionStoreRequest;
use App\Http\Requests\SectionUpdateRequest;
use App\Model\Location\LibOfficeDepartment;
use App\Model\Location\LibDepartmentSection;
use App\Http\Requests\DepartmentStoreRequest;
use App\Http\Requests\DepartmentUpdateRequest;

class LocationController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');

        $this->lib_office = new LibOffice;
        $this->lib_department = new LibDepartment;
        $this->lib_section = new LibSection;        
        $this->lib_department_section = new LibDepartmentSection;
        $this->lib_office_department = new LibOfficeDepartment;
    }
    
    public function offices()
    {
        abort_unless(\Gate::allows('office_view'), 403);
        $offices =  $this->lib_office->listOfOffices();
        return view('location-management.offices', compact('offices'));
    }
            public function createOffice(){
                $departments =  $this->lib_department->listOfEnabledDepartments();
                return json_encode($departments);
            }
            public function storeOffice(OfficeStoreRequest $request)
            {
                abort_unless(\Gate::allows('office_store'), 403);
                //save office first
                $officeId = $this->lib_office->storeOfficeReturnId($request);
                //then all assigned departments
                $this->lib_office_department->storeDepartmentOffice($officeId, $request);

                $notification = array(
                    'message' => 'Successfully saved.',
                    'alert-type' => 'success'
                );

                return Redirect()->back()->with($notification);
            }
            public function editOffice($hid)
            {
                $id = Hasher::decode($hid);
                $office = $this->lib_office->searchOfficeById($id);
                $departments = $this->lib_department->listOfEnabledDepartments(); // all departments except softdeleted
                $selected_departments = $this->lib_office_department->showDepartmentsByOfficeId($id);

                $arr = array('office' => $office, 
                            'departments' => $departments, 
                            'selected_departments' => $selected_departments);

                return json_encode($arr);
            }
            public function updateOffice(OfficeUpdateRequest $request)
            {
                abort_unless(\Gate::allows('office_update'), 403);
                $id = Hasher::decode($request->hashid);
                //update role details
                $withUpdate = $this->lib_office->updateOffice($id, $request);
                //delete all department from office first
                $this->lib_office_department->deleteDepartmentOfficeByOfficeId($id);
                //then store all selected departments
                $this->lib_office_department->storeDepartmentOffice($id, $request);

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
            public function disableOffice($hashid){
                abort_unless(\Gate::allows('office_disable'), 403);
                $id = Hasher::decode($hashid);
                $this->lib_office->disableOffice($id);
                $notification=array(
                    'message'=>'Office has been disabled.',
                    'alert-type'=>'success'
                );
                return Redirect()->back()->with($notification);
            }        
            public function enableOffice($hashid){
                abort_unless(\Gate::allows('office_enable'), 403);
                $id = Hasher::decode($hashid);
                $this->lib_office->enableOffice($id);
                $notification=array(
                    'message'=>'Office has been enabled.',
                    'alert-type'=>'success'
                );
                return Redirect()->back()->with($notification);
            }
    public function departments()
    {
        abort_unless(\Gate::allows('department_view'), 403);
        $departments =  $this->lib_department->listOfDepartments();
        return view('location-management.departments', compact('departments'));
    }
            public function createDepartment(){
                $sections =  $this->lib_section->listOfEnabledSections();
                return json_encode($sections);
            }
            public function storeDepartment(DepartmentStoreRequest $request)
            {
                abort_unless(\Gate::allows('department_store'), 403);
                //save department first
                $departmentId = $this->lib_department->storeDepartmentReturnId($request);
                //then all assigned sections
                $this->lib_department_section->storeSectionDepartment($departmentId, $request);

                $notification = array(
                    'message' => 'Successfully saved.',
                    'alert-type' => 'success'
                );

                return Redirect()->back()->with($notification);
            }
            public function editDepartment($hid)
            {
                $id = Hasher::decode($hid);
                $department = $this->lib_department->searchDepartmentById($id);
                $sections = $this->lib_section->listOfEnabledSections(); // all sections except softdeleted
                $selected_sections = $this->lib_department_section->showSectionsByDepartmentId($id);

                $arr = array('department' => $department, 
                            'sections' => $sections, 
                            'selected_sections' => $selected_sections);

                return json_encode($arr);
            }
            public function updateDepartment(DepartmentUpdateRequest $request)
            {
                abort_unless(\Gate::allows('department_update'), 403);
                $id = Hasher::decode($request->hashid);
                //update department details
                $withUpdate = $this->lib_department->updateDepartment($id, $request);
                //delete all sections from department first
                $this->lib_department_section->deleteSectionDepartmentByDeptId($id);
                //then store all selected sections
                $this->lib_department_section->storeSectionDepartment($id, $request);

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
            public function disableDepartment($hashid){
                abort_unless(\Gate::allows('department_disable'), 403);
                $id = Hasher::decode($hashid);
                $this->lib_department->disableDepartment($id);
                $notification=array(
                    'message'=>'Department has been disabled.',
                    'alert-type'=>'success'
                );
                return Redirect()->back()->with($notification);
            }        
            public function enableDepartment($hashid){
                abort_unless(\Gate::allows('department_enable'), 403);
                $id = Hasher::decode($hashid);
                $this->lib_department->enableDepartment($id);
                $notification=array(
                    'message'=>'Department has been enabled.',
                    'alert-type'=>'success'
                );
                return Redirect()->back()->with($notification);
            }
            public function showDepartmentsByOfficeId($hashofficeid){ //profile-edit.blade, register.blade
                if (Str::length($hashofficeid) >= 5){ //it is hashed
                    $id = Hasher::decode($hashofficeid);
                } else { // not hashed
                    $id = $hashofficeid;
                }                
                $departments =  $this->lib_office_department->showDepartmentsByOfficeId($id);
                return json_encode($departments);
            }

    public function sections()
    {
        abort_unless(\Gate::allows('section_view'), 403);
        $sections =  $this->lib_section->listOfSections();
        return view('location-management.sections', compact('sections'));
    }
            public function storeSection(SectionStoreRequest $request)
            {
                abort_unless(\Gate::allows('section_store'), 403);
                $this->lib_section->storeSection($request);
                $notification = array(
                    'message' => 'Successfully saved.',
                    'alert-type' => 'success'
                );

                return Redirect()->back()->with($notification);
            }
            public function editSection($hashid)
            {
                $id = Hasher::decode($hashid);
                $full_details =  $this->lib_section->searchSectionById($id);
                return json_encode($full_details);
            }
            public function updateSection(SectionUpdateRequest $request)
            {
                abort_unless(\Gate::allows('section_update'), 403);
                $id = Hasher::decode($request->hashid);
                $withUpdate = $this->lib_section->updateSection($id, $request);
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
            public function disableSection($hashid){
                abort_unless(\Gate::allows('section_disable'), 403);
                $id = Hasher::decode($hashid);
                $this->lib_section->disableSection($id);
                $notification=array(
                    'message'=>'Section has been disabled.',
                    'alert-type'=>'success'
                );
                return Redirect()->back()->with($notification);
            }        
            public function enableSection($hashid){
                abort_unless(\Gate::allows('section_enable'), 403);
                $id = Hasher::decode($hashid);
                $this->lib_section->enableSection($id);
                $notification=array(
                    'message'=>'Section has been enabled.',
                    'alert-type'=>'success'
                );
                return Redirect()->back()->with($notification);
            }
            public function showSectionsByDepartmentId($hashdeptid){ //UserController. profileEdit function. profile-edit.blade, register.blade.
                if (Str::length($hashdeptid) >= 5){ //it is hashed
                    $deptId = Hasher::decode($hashdeptid);
                } else { //not hashed
                    $deptId = $hashdeptid;
                }                  
                $sections =  $this->lib_department_section->showSectionsByDepartmentId($deptId);
                return json_encode($sections);
            }     

}
