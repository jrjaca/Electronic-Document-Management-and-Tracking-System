<?php

namespace App\Http\Controllers;

use App\Role;
use App\Setting;
use App\RoleUser;
use App\LibDocumentType;
use App\Services\Hasher;
use Illuminate\Http\Request;
use App\Model\Publish\Publish;
use App\Model\Location\LibOffice;
use App\Model\Location\LibSection;
use App\Model\Publish\Announcement;
use Illuminate\Support\Facades\Auth;
use App\Model\Location\LibDepartment;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
        $this->role = new Role;
        $this->lib_office = new LibOffice();
        $this->lib_department = new LibDepartment();
        $this->lib_section = new LibSection();
        $this->announcement = new Announcement();
        $this->publish = new Publish();
        $this->libdocumentType = new LibDocumentType();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        // /http://localhost:9002/ecommerce-orders
        //abort_unless(\Gate::allows('view_orders'), 403);
        if(view()->exists($request->path())){
            return view($request->path());
        }
        return view('pages-404');
    }

    public function home(){
        $announcement = $this->announcement->getAnnouncementDetail();

        //GetPublishedDocsByDocTypeLocCount($isLoggedIn, $typeId, $officeId, $deptId, $count)
        $isLoggedIn = false;
        $officeId = null;
        $deptId = null;
        if (Auth::check()) {
            $isLoggedIn = true;
            $officeId = Auth::user()->office_id;
            $deptId = Auth::user()->department_id;
        }

        //used by Home index.blade.php
        //GetPublishedDocsByDocTypeLocCount($isLoggedIn, $docTypeId, $officeId, $deptId, $count)
        $cpo_list = $this->publish->GetPublishedDocsByDocTypeLocCount($isLoggedIn, '1', $officeId, $deptId, 3); //1-CPO
        $dtr_list = $this->publish->GetPublishedDocsByDocTypeLocCount($isLoggedIn, '2', $officeId, $deptId, 3); //2-DTR
        $ot_list = $this->publish->GetPublishedDocsByDocTypeLocCount($isLoggedIn, '3', $officeId, $deptId, 3); //3-OT

        //MANUALLY ADD HERE FOR ANOTHER ROW!!!!!!!!

        return view('index', compact('announcement', 'cpo_list', 'dtr_list', 'ot_list'));
        //return view('index');
    }

    public function about()
    {
        $office = $this->lib_office->searchOfficeById(\Auth::user()->office_id);
        $department = $this->lib_department->searchDepartmentById(\Auth::user()->department_id);
        $section = $this->lib_section->searchSectionById(\Auth::user()->section_id);
        $role = $this->role->searchRoleByUserId(\Auth::id());

        $arr = array('office' => $office, 
                    'department' => $department,
                    'section' => $section,
                    'role' => $role);
        return json_encode($arr);
    }

    public function viewPublishedDocFullDetails($hashPublishedId){
        //abort_unless(\Gate::allows('view_publish_document'), 403);
        $published_id = Hasher::decode($hashPublishedId);
        $res = $this->publish->GetPublishedDocsFullDetailsByPublishedId($published_id);
        return json_encode($res);
    } 

    public function listDocByType($hashedDocTypeId){
        $docTypeId = Hasher::decode($hashedDocTypeId);

        //GetPublishedDocsByDocTypeLocCount($isLoggedIn, $typeId, $officeId, $deptId, $count)
        $isLoggedIn = false;
        $officeId = null;
        $deptId = null;
        if (Auth::check()) {
            $isLoggedIn = true;
            $officeId = Auth::user()->office_id;
            $deptId = Auth::user()->department_id;
        }

        //used by Home index.blade.php
        $published_docs = $this->publish->GetPublishedDocsByDocTypeLocCount($isLoggedIn, $docTypeId, $officeId, $deptId, 1000000000000);
        //dd($published_docs);
        return view('list-bytype', compact('published_docs'));
    }

    public function downloadFile($hasedPubId){ 
        //$myFile = storage_path("folder/dummy_pdf.pdf");
        //$pathAndFileName = "storage/publish/210606-165318-407_ITW_cat1.jpg";
        $pubId = Hasher::decode($hasedPubId);
        $info = Publish::withTrashed()->where('published_id', $pubId)->first();
        //dd($info);
        //$info = Publish::find($pubId);
        $file = public_path($info->path);
        return response()->download($file);
    }

    public function advancedSearch(){
        $document_types = $this->libdocumentType->listOfDocumentTypesEnabledOnly();
        $is_searched = false;
        $published_docs = "";
        return view('advanced-search', compact('document_types', 'published_docs', 'is_searched'));
    }

    public function searching(Request $request){
        $document_types = $this->libdocumentType->listOfDocumentTypesEnabledOnly();
        $is_searched = true;

        $docTypeId = $request['document_type_id'];
        $docName = $request['title'];
        $fileName = $request['attachment_name'];
        $pubStartDate = $request['published_at_start'];
        $pubEndDate = $request['published_at_end'];
        $remarks = $request['remarks'];

        if ($pubStartDate != null || $pubEndDate != null){ //if published date start or end has a value
            $rules = array(
                'published_at_start' => ['required_with:published_at_end'], //file //if is_hardcopy is false, require attachment
                'published_at_end' => ['required_with:published_at_start'],
                'published_at_start' => ['before_or_equal:published_at_end'],
                'document_type_id' => ['required_without_all:title,attachment_name,published_at_start,published_at_end,remarks'],
            );    
            $messages = array(
                'published_at_start.required_with' => 'Published start date is also required.',
                'published_at_end.required_with' => 'Published end date is also required.',
                'published_at_start.before_or_equal' => 'Published start date must not be later than the published end date.',
                'document_type_id.required_without_all' => 'Please provide at least one criteria.',
            );
        } else {
            $rules = array(
                'published_at_start' => ['required_with:published_at_end'], //file //if is_hardcopy is false, require attachment
                'published_at_end' => ['required_with:published_at_start'],
                'document_type_id' => ['required_without_all:title,attachment_name,published_at_start,published_at_end,remarks'],
            );    
            $messages = array(
                'published_at_start.required_with' => 'Published start date is also required.',
                'published_at_end.required_with' => 'Published end date is also required.',
                'document_type_id.required_without_all' => 'Please provide at least one criteria.',
            );
        }    
        
        $validator = Validator::make( $request->all(), $rules, $messages );                
        if ($validator->fails()){
            return Redirect()->back()->withInput()->withErrors($validator->messages()->get('*')); 
        } 

        //GetPublishedDocByAdvCriteria($isLoggedIn, $officeId, $deptId, $docTypeId, $docName, $fileName, $pubStartDate, $pubEndDate, $remarks); 
        $isLoggedIn = false;
        $officeId = null;
        $deptId = null;
        if (Auth::check()) {
            $isLoggedIn = true;
            $officeId = Auth::user()->office_id;
            $deptId = Auth::user()->department_id;
        }
        $published_docs = $this->publish->GetPublishedDocByAdvCriteria($isLoggedIn, $officeId, $deptId, $docTypeId, $docName, $fileName, $pubStartDate, $pubEndDate, $remarks); 
        return view('advanced-search', compact('document_types', 'published_docs', 'is_searched'));
    }

}

