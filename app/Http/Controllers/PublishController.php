<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\LibDocumentType;
use App\Model\Document\AttachedDocument;
use App\Model\Document\Document;
use App\Services\Hasher;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Model\Publish\Publish;
use App\Model\Location\LibOffice;
use App\Model\Location\LibOfficeDepartment;
use App\Model\Publish\Announcement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PublishController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->Announcement = new Announcement();
        $this->Document = new Document();
        $this->AttachedDocument = new AttachedDocument();
        $this->Publish = new Publish();
        $this->LibDocumentType = new LibDocumentType();
        $this->LibOffice = new LibOffice();
        $this->LibOfficeDepartment = new LibOfficeDepartment();
    }

//////////////////  UPLOAD CREATE FILE TYPE and upload sample
// sa docmanagenebt, tag as terminal and track doc.  sa main track doc, released, received , terminal
//at main page, create 6 cat and button belo "advanced search"

//add date submitted, last office location and date received, last status response(if approved or RTH)


    public function create(){
        abort_unless(\Gate::allows('publish_document'), 403);
        //return view('publish.publish-announcement', compact(''document_types''));
        $document_types = $this->LibDocumentType->listOfDocumentTypesEnabledOnly();
        $offices = $this->LibOffice->listOfEnabledOffices();
        return view('publish.create', compact('document_types', 'offices'));
    }    
            public function createPublishedRoutedDoc($hashedDocId){ //page to upload document from previously created/submitted or routed document
                abort_unless(\Gate::allows('publish_document'), 403);
                $document_id = Hasher::decode($hashedDocId);
                $document = $this->Document->getDocumentBasicDetailsByDocId($document_id);

                //check if already published
                $isPublished = $this->Publish->GetPublishedDocsByDocumentId($document_id); 
                if ($isPublished){
                    $notification = array(
                        'message' => "Document ".$document->title." has already been published.",
                        'alert-type' => 'info'
                    );
                    return Redirect()->back()->with($notification);
                }
                
                $attached_doc = $this->AttachedDocument->getFileInfoEnabledOnlyByDocId($document_id);
                $document_types = $this->LibDocumentType->listOfDocumentTypesEnabledOnly();
                $offices = $this->LibOffice->listOfEnabledOffices();
                return view('publish.published-routed', compact('document', 'attached_doc', 'document_types', 'offices'));
            }

    public function submit(Request $request){
        abort_unless(\Gate::allows('publish_document'), 403);
        //dd($request);

        //$isDraft = ($request['pub_btn'] == 'draft_pub' ? true : false);
        $rules = array(
            'title' => 'required|string',
            'document_type_id' => 'required',
            'attachedFile' => ['required_without:is_hardcopy','max:10999', 'mimes:jpeg,png,pdf,doc,docx,xls,xlsx,ppt,pptx'], //file //if is_hardcopy is false, require attachment
        );    
        $messages = array(
            'title.required' => 'Document name is required.',
            'title.string' => 'Document name must be a string.',
            'document_type_id.required' => 'Document type is required.',
            'attachedFile.required_without' => 'Attach File is required.',
            'attachedFile.max' => 'File size must not be greater than 10 MB.',
            'attachedFile.mimes' => 'Attached file must be of the following format: jpeg, png, pdf, doc, docx, xls, xlsx, ppt, pptx.',
        );
        
        $validator = Validator::make( $request->all(), $rules, $messages );                
        if ($validator->fails()){
            return Redirect()->back()->withInput()->withErrors($validator->messages()->get('*')); 
        } 
        
        $currentDateTime = Carbon::now('Asia/Manila')->format('ymd-His');
        $raw_code = $currentDateTime.'-'.substr(Auth::user()->username, -3);

        //save attachment  //attachedFile
        $file = $request->file('attachedFile');
        $attachmentNameOrig = "";
        $fullPathAndName = "";
        $attachmentExtension = "";
        $attachmentSize = "";
        if ($file != null){ //with attached file
            $upload_path = 'storage/publish/'; //public local folder
            $attachmentNameOrig = $file->getClientOriginalName(); //attachment_name //visble to user
            $attachmentExtension = $file->getClientOriginalExtension();
            $attachmentSize = $file->getSize();
            $attachmentNameInStorage = $raw_code.'_'.(Str::random(3)).'_'.preg_replace('/\s+/', '', $attachmentNameOrig); //attachment name in storage without whitespaces
            $fullPathAndName = $upload_path.$attachmentNameInStorage; //save in Db at path attrib
            $file->move(public_path().'/'.$upload_path, $attachmentNameInStorage); //move file to public folder //use move since direct saving to public folder
        } 

            //$publishedAt = ($isDraft ? null : Carbon::now('Asia/Manila'));
            $doc_arr = array(
                //'document_id' => $document_id,
                'is_published' => true,//$isDraft, //published
                'user_id' => Auth::user()->id, //creator
                'extension' => $attachmentExtension,
                'size' => $attachmentSize,
                'attachment_name' => $attachmentNameOrig,
                'path' => $fullPathAndName, 
                'title' => $request['title'],
                'remarks' => $request['remarks'], 
                'document_type_id' => $request['document_type_id'], 
                'office_id' => $request['office'], 
                'department_id' => $request['department'], 
                //'section_id' => $request['section'], 
                'published_at' => Carbon::now('Asia/Manila'),//$publishedAt,
                'created_at' => Carbon::now('Asia/Manila')
            );        
            //$this->Publish->store($doc_arr);
            Publish::insert($doc_arr);

        //$savingStat = ($isDraft ? "Draft document has been successfully saved." : "Document has been successfully published.");
        $notification = array(
            'message' => "Document has been successfully published.",//$savingStat,
            'alert-type' => 'success'
        );
        return Redirect()->back()->with($notification);
        //direct sa list
    }
            public function submitPublishedRoutedDoc(Request $request){ //upload document from previously created/submitted or routed document
                abort_unless(\Gate::allows('publish_document'), 403);
                //dd($request);

                //$isDraft = ($request['pub_btn'] == 'draft_pub' ? true : false);
                $rules = array(
                    'title' => 'required|string',
                    'document_type_id' => 'required',
                    //'attachedFile' => ['required_without:is_hardcopy','max:10999', 'mimes:jpeg,png,pdf,doc,docx,xls,xlsx,ppt,pptx'], //file //if is_hardcopy is false, require attachment
                );    
                $messages = array(
                    'title.required' => 'Document name is required.',
                    'title.string' => 'Document name must be a string.',
                    'document_type_id.required' => 'Document type is required.',
                    //'attachedFile.required_without' => 'Attach File is required.',
                    //'attachedFile.max' => 'File size must not be greater than 10 MB.',
                    //'attachedFile.mimes' => 'Attached file must be of the following format: jpeg, png, pdf, doc, docx, xls, xlsx, ppt, pptx.',
                );
                
                $validator = Validator::make( $request->all(), $rules, $messages );                
                if ($validator->fails()){
                    return Redirect()->back()->withInput()->withErrors($validator->messages()->get('*')); 
                } 
                
                $currentDateTime = Carbon::now('Asia/Manila')->format('ymd-His');
                $raw_code = $currentDateTime.'-'.substr(Auth::user()->username, -3);

                //save attachment  //attachedFile
                // $file = $request->file('attachedFile');
                // $attachmentNameOrig = "";
                // $fullPathAndName = "";
                // $attachmentExtension = "";
                // $attachmentSize = "";
                // if ($file != null){ //with attached file
                //     $upload_path = 'storage/publish/'; //public local folder
                //     $attachmentNameOrig = $file->getClientOriginalName(); //attachment_name //visble to user
                //     $attachmentExtension = $file->getClientOriginalExtension();
                //     $attachmentSize = $file->getSize();
                //     $attachmentNameInStorage = $raw_code.'_'.(Str::random(3)).'_'.preg_replace('/\s+/', '', $attachmentNameOrig); //attachment name in storage without whitespaces
                //     $fullPathAndName = $upload_path.$attachmentNameInStorage; //save in Db at path attrib
                //     $file->move(public_path().'/'.$upload_path, $attachmentNameInStorage); //move file to public folder //use move since direct saving to public folder
                // } 

                    //$publishedAt = ($isDraft ? null : Carbon::now('Asia/Manila'));
                    $doc_arr = array(
                        'document_id' => $request['document_id'],
                        'is_published' => true,//$isDraft, //published
                        'user_id' => Auth::user()->id, //creator
                        'extension' => $request['extension'],
                        'size' => $request['size'],
                        'attachment_name' => $request['attachment_name'],
                        'path' => $request['path'], 
                        'title' => $request['title'],
                        'remarks' => $request['remarks'], 
                        'document_type_id' => $request['document_type_id'], 
                        'office_id' => $request['office'], 
                        'department_id' => $request['department'], 
                        //'section_id' => $request['section'], 
                        'published_at' => Carbon::now('Asia/Manila'),//$publishedAt,
                        'created_at' => Carbon::now('Asia/Manila')
                    );        
                    //$this->Publish->store($doc_arr);
                    Publish::insert($doc_arr);

                //$savingStat = ($isDraft ? "Draft document has been successfully saved." : "Document has been successfully published.");
                $notification = array(
                    'message' => "Document has been successfully published.",//$savingStat,
                    'alert-type' => 'success'
                );
                //return Redirect()->back()->with($notification);
                return Redirect()->route('publish.list')->with($notification);
                //direct sa list
            }

    public function listPublished(){
        abort_unless(\Gate::allows('view_publish_document'), 403);
        $userId = Auth::user()->id;
        $published_docs = $this->Publish->GetPublishedDocsByUserId($userId);
        return view('publish.list', compact('published_docs'));
    }

    public function editPublishedDoc($hashPublishedId){
        abort_unless(\Gate::allows('update_publish_document'), 403);
        $published_id = Hasher::decode($hashPublishedId);
        $publish = $this->Publish->GetPublishedDocsByPublishedId($published_id);
        $document_types = $this->LibDocumentType->listOfDocumentTypesEnabledOnly();        
        $offices = $this->LibOffice->listOfEnabledOffices();
        $departments_per_office = $this->LibOfficeDepartment->showDepartmentsByOfficeId($publish['office_id']);
        return view('publish.edit', compact('publish', 'document_types', 'offices', 'departments_per_office'));
    }
    
    public function updatePublishedDoc(Request $request){
        abort_unless(\Gate::allows('update_publish_document'), 403);
        $data = Publish::find($request['published_id']);
        $data->updated_byuser_id = Auth::user()->id;
        $data->title = $request['title'];
        $data->document_type_id = $request['document_type_id'];
        $data->remarks = $request['remarks'];
        $data->office_id = $request['office'];
        $data->department_id = $request['department'];
        $withUpdateInDocument = $data->isDirty();
        $data->save();

        if ($withUpdateInDocument){  
            $notification = array(
                'message' => "Successfully updated.",
                'alert-type' => 'success'
            );
        } else {
            $notification = array(
                'message' => "No changes have been made.",
                'alert-type' => 'info'
            );
        }    
        return Redirect()->route('publish.list')->with($notification);
    }    

    public function temporarilyDeletePublishedDoc($hashPublishedId){
        abort_unless(\Gate::allows('unpublish_document'), 403);
        $published_id = Hasher::decode($hashPublishedId);
        //update is_publish
        $this->Publish->taggedAsUnpublished($published_id);
        //temporarily delete
        Publish::find($published_id)->delete(); 
        $notification = array(
            'message' => "Temporary Deletion has been successfull.",
            'alert-type' => 'success'
        );
        return Redirect()->back()->with($notification);
    }

    public function listTemporaryDeleted(){
        abort_unless(\Gate::allows('view_deleted_publish_document'), 403);
        $userId = Auth::user()->id;
        $published_docs = $this->Publish->GetTempDeletedPublishedDocsByUserId($userId);
        return view('publish.deleted-list', compact('published_docs'));
    }

    public function rePublishDoc($hashPublishedId){
        abort_unless(\Gate::allows('publish_document'), 403);
        $published_id = Hasher::decode($hashPublishedId);
        //enable/re-publish
        Publish::withTrashed()->find($published_id)->restore();
        //tag as published
        $this->Publish->taggedAsPublished($published_id);
        $notification = array(
            'message' => "Re-Published has been successfull.",
            'alert-type' => 'success'
        );
        return Redirect()->back()->with($notification);
    }

    //-----ANNOUNCEMENT-----//
    public function createAnnouncement(){
        abort_unless(\Gate::allows('update_publish_announcement'), 403);
        $announcement = $this->Announcement->getAnnouncementDetail();
        return view('publish.publish-announcement', compact('announcement'));
    }

    public function submitAnnouncement(Request $request){
        abort_unless(\Gate::allows('update_publish_announcement'), 403);
        //update
        $data = Announcement::find($request['announcement_id']);
        $data->title = $request['title'];
        $data->sub_title = $request['sub_title'];
        $data->details = $request['details'];
        $data->updated_byuser_id = Auth::user()->id;
        //$data->updated_at = Carbon::now('Asia/Manila');
        $withUpdate = $data->isDirty();
        $data->save();

        if ($withUpdate){
            $notification = array(
                'message' => 'Successfully updated.',
                'alert-type' => 'success'
            );
        } else {
            $notification = array(
                'message' => 'No data has been changed.',
                'alert-type' => 'info'
            );
        }
        
        return Redirect()->back()->with($notification);

    }

// 53 publish_document
// 54 view_publish_document
// 55 unpublish_document
// 56 update_publish_document
// 57 temporarydelete_publish_document
// 58 permanentlydelete_publish_document

// 59 publish_announcement
// 60 update_publish_announcement
// 61 view_deleted_publish_document
}
