<?php

namespace App\Http\Controllers;

use App\Jobs\SendCreateDocumentEmail;
use App\User;
use Carbon\Carbon;
use App\Jobs\SendEmail;
use App\LibDocumentType;
use App\Services\Hasher;
use App\LibDocumentAction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Model\Document\Route;
use App\Model\Document\SeenLog;
use App\Mail\CreateDocumentMail;
use App\Model\Document\Document;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use App\Model\Document\UserForwarded;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use App\Model\Document\AttachedDocument;
use Illuminate\Support\Facades\Validator;

class DocumentController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->LibDocumentType = new LibDocumentType();
        $this->LibDocumentAction = new LibDocumentAction();
        $this->Document = new Document();
        $this->Route = new Route();
        $this->UserForwarded = new UserForwarded();
        $this->AttachedDocument = new AttachedDocument();
        $this->User = new User();
        $this->SeenLog = new SeenLog();
    }

    public function create(){
        abort_unless(\Gate::allows('create_document'), 403);
        $currentDateTime = Carbon::now('Asia/Manila')->format('ymd-His');
        $raw_code = $currentDateTime.'-'.substr(Auth::user()->username, -3);
        $document_types = $this->LibDocumentType->listOfDocumentTypesEnabledOnly();
        $document_actions = $this->LibDocumentAction->listOfDocumentActionsEnabledOnly();//listOfDocumentActions();
        $users = $this->User->listOfAllEnabledUsers(); 
        return view('document-management.create', compact('raw_code', 'document_types', 'document_actions', 'users'));
    }
            public function submit(Request $request){ 
                //mag add para sa create, other validation
                abort_unless(\Gate::allows('create_document'), 403);
                $btn = $request['save_btn'];

                if ($btn == "submit_val" || $btn == "draft_val") { //SUBMIT and DRAFT: new record ( create view )
                    $rules = array(
                        'title' => 'required|string',
                        'document_type_id' => 'required',
                        'document_action_id' => 'required',
                        'outer-group.0.forwarded_to_users' => 'required', //array
                        'attachedFile' => ['required_without:is_hardcopy','max:10999', 'mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,ppt,pptx'], //file //if is_hardcopy is false, require attachment
                    );    
                    $messages = array(
                        'title.required' => 'Document name is required.',
                        'title.string' => 'Document name must be a string.',
                        'document_type_id.required' => 'Document type is required.',
                        'document_action_id.required' => 'Purpose of document is required (For).',
                        'outer-group.0.forwarded_to_users.required' => 'Provide at least one recipient/user (Forward To).',
                        'attachedFile.required_without' => 'Attach File is required.',
                        'attachedFile.max' => 'File size must not be greater than 10 MB.',
                        'attachedFile.mimes' => 'Attached file must be of the following format: jpg, jpeg, png, pdf, doc, docx, xls, xlsx, ppt, pptx.',
                    );
                } elseif ($btn == "submit_draft_val" || $btn == "update_draft_val") { //SUBMIT with existing doc or Update Draft ( update view )
                    $rules = array(
                        'title' => 'required|string',
                        'document_type_id' => 'required',
                        'document_action_id' => 'required',
                        'outer-group.0.forwarded_to_users' => 'required', //array
                        'attachedFile' => ['required_without_all:is_hardcopy,old_path','max:10999', 'mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,ppt,pptx'], //file //if is_hardcopy is false and old_path is blank, require attachment
                    );    
                    $messages = array(
                        'title.required' => 'Document name is required.',
                        'title.string' => 'Document name must be a string.',
                        'document_type_id.required' => 'Document type is required.',
                        'document_action_id.required' => 'Purpose of document is required (For).',
                        'outer-group.0.forwarded_to_users.required' => 'Provide at least one recipient/user (Forward To).',
                        'attachedFile.required_without_all' => 'Attach File is required, since this has no old attachment and is not a hard copy.',
                        'attachedFile.max' => 'File size must not be greater than 10 MB.',
                        'attachedFile.mimes' => 'Attached file must be of the following format: jpg, jpeg, png, pdf, doc, docx, xls, xlsx, ppt, pptx.',
                    );
                }
                
                $validator = Validator::make( $request->all(), $rules, $messages );                
                if ($validator->fails()){
                    return Redirect()->back()->withInput()->withErrors($validator->messages()->get('*')); 
                } 

                if ($btn == "submit_val" || $btn == "draft_val") { //SUBMIT and DRAFT: new record ( create view )
                    $res = $this->saveNewDocument($request);
                } elseif ($btn == "submit_draft_val" || $btn == "update_draft_val") { //SUBMIT with existing doc or Update Draft ( update view )
                    $res = $this->submitOrUpdateDraftDocument($request);
                } 

                return $res; // Final notification
            }
                    public function saveNewDocument($request){ //SUBMIT and DRAFT: new record ( create view )
                        $email_notification = (isset($request['email_notification'][0]) ? 1 : 0);
                        $is_confidential = (isset($request['outer-group'][0]['is_confidential']) ? 1 : 0);
                        $is_hardcopy = (isset($request['is_hardcopy']) ? 1 : 0);        
                        $is_draft = ($request['save_btn'] == 'draft_val' ? 1 : 0); //CREATE BLADE:draft_val, submit_val
                        $barcode = $request['barcode'];
                        $title = $request['title'];
                
                        // if ($is_hardcopy){ $status = '1'; } //released thru routing
                        // else { $status = 'L'; } //sent released thru electronic

                        //1. Save Document
                        if(!$is_draft) { $submittedAt = Carbon::now('Asia/Manila'); }
                        else           { $submittedAt = null; }
                        
                        $document_arr = array(  //submit or draft
                            'barcode' => $barcode,
                            'user_id' => Auth::user()->id,
                            'title' => $title,
                            'document_type_id' => $request['document_type_id'],
                            'document_action_id' => $request['document_action_id'],
                            'remarks' => $request['remarks'],
                            'email_notification' => $email_notification,
                            'is_confidential' => $is_confidential,
                            'is_hardcopy' => $is_hardcopy,
                            'is_draft' => $is_draft,
                            //'courier_user_id' => $request['courier_user_id'],
                            'created_at' => Carbon::now('Asia/Manila'),
                            'submitted_at' => $submittedAt
                        );        
                        //$document_id = $this->Document->storeGetDocumentId($document_arr);
                        $document_id = Document::insertGetId($document_arr);

                        //2. save route (after submission only, except draft)
                        if(!$is_draft){
                            $route_id = $this->Route->insertRouteGetId(//$document_id, 
                                                                        Auth::user()->id, 
                                                                        $document_id,
                                                                        $title,
                                                                        $request['document_type_id'],
                                                                        $request['document_action_id'],
                                                                        'L',//$status, 
                                                                        Auth::user()->office_id, 
                                                                        Auth::user()->department_id,
                                                                        Auth::user()->section_id, 
                                                                        Carbon::now('Asia/Manila'));
                            // $route_arr = array(
                            //     'document_id' => $document_id,
                            //     'user_id' => Auth::user()->id, //s
                            //     'document_action_id' => $request['document_action_id'],
                            //     'status' => $status,  //Released
                            //     'office_id' => Auth::user()->office_id,
                            //     'department_id' => Auth::user()->department_id,
                            //     'section_id' => Auth::user()->section_id,
                            //     'created_at' => Carbon::now('Asia/Manila')
                            // );        
                            // //$this->Route->store($route_arr);
                            // $route_id = Route::insertGetId($route_arr);
                        }    

                        //3. save recipients / users
                        foreach ($request['outer-group'][0]['forwarded_to_users'] as $key => $value){
                            //get recipient location details
                            $userLimitedDetails = $this->User->getUserLimitedDetails($value);                             
                            $recipient_arr = array(
                                'document_id' => $document_id,
                                'user_id' => $value,
                                'office_id' => $userLimitedDetails->office_id,
                                'department_id' => $userLimitedDetails->department_id,
                                'section_id' => $userLimitedDetails->section_id
                            );

                            $recipient_email_arr[] = array(
                                //for sending of notification, later use
                                'e_last_name' => $userLimitedDetails->last_name,
                                'e_first_name' => $userLimitedDetails->first_name,
                                'e_middle_name' => $userLimitedDetails->middle_name,
                                'e_suffix_name' => $userLimitedDetails->suffix_name,
                                'e_email' => $userLimitedDetails->email
                            );
                            //$this->UserForwarded->store($recipient_arr);
                            UserForwarded::insert($recipient_arr);
                        }

                        //4. save attachment  //attachedFile
                        $file = $request->file('attachedFile');
                        $attachmentNameOrig = "";
                        $fullPathAndName = "";
                        $attachmentExtension = "";
                        $attachmentSize = "";
                        if ($file != null){ //with attached file
                            $upload_path = 'storage/attachments/'; //public local folder
                            $attachmentNameOrig = $file->getClientOriginalName(); //attachment_name //visble to user
                            $attachmentExtension = $file->getClientOriginalExtension();
                            $attachmentSize = $file->getSize();
                            $attachmentNameInStorage = $barcode.'_'.$document_id.'_'.(Str::random(3)).'_'.preg_replace('/\s+/', '', $attachmentNameOrig); //attachment name in storage without whitespaces
                            $fullPathAndName = $upload_path.$attachmentNameInStorage; //save in Db at path attrib
                            $file->move(public_path().'/'.$upload_path, $attachmentNameInStorage); //move file to public folder //use move since direct saving to public folder
                        } 
                            $attachment_arr = array(
                                'document_id' => $document_id,
                                'user_id' => Auth::user()->id, //creator
                                'extension' => $attachmentExtension,
                                'size' => $attachmentSize,
                                'attachment_name' => $attachmentNameOrig,
                                'path' => $fullPathAndName, 
                                'external_link' => $request['external_link'],
                                'created_at' => Carbon::now('Asia/Manila')
                            );        
                            //$this->AttachedDocument->store($attachment_arr);
                            $attached_document_id = AttachedDocument::insertGetId($attachment_arr);

                        //5. Save seen logs (after submission only, except draft)
                        if(!$is_draft){
                            foreach ($request['outer-group'][0]['forwarded_to_users'] as $key => $value){ //recipients, to monitor their seen log
                                $this->SeenLog->insertSeenLog($route_id,
                                                                $document_id,
                                                                $attached_document_id,
                                                                $request['document_action_id'],
                                                                Auth::user()->id,   //sender
                                                                $value, //recipient
                                                                $request['remarks'],   //reply
                                                                Carbon::now('Asia/Manila'));
                                // $seenlog_arr = array(
                                //     'route_id' => $route_id,
                                //     'attached_document_id' => $attached_document_id,
                                //     'document_id' => $document_id,
                                //     'sender_id' => Auth::user()->id,
                                //     'recipient_id' => $value,
                                //     'submitted_at' => Carbon::now('Asia/Manila')
                                // );        
                                // SeenLog::insert($seenlog_arr);
                            }
                        }    

                        $savingStat = ($request['save_btn'] == 'draft_val' ? "Draft document has been successfully saved." : "Document has been successfully sent.");
                        $notification = array(
                            'message' => $savingStat,
                            'alert-type' => 'success'
                        );

                        if ($is_draft){
                            return Redirect()->route('document.draft.list')->with($notification);
                        } else {
                            //SEND EMAIL NOTIFICATION
                            $data['barcode'] = $barcode;
                            $data['title'] = $title;
                            $data['hashed_docid'] = Hasher::encode($document_id);
                            $data['hashed_senderid'] = Hasher::encode(Auth::user()->id);
                            $data['sender_name'] = Auth::user()->first_name.' '.Auth::user()->middle_name.' '.Auth::user()->last_name.' '.Auth::user()->suffix_name;
                            $data['subject'] = "New Document ".$title;
                            foreach ($recipient_email_arr as $key =>  $recipient) {                                
                                $data['recipient_name'] = $recipient['e_first_name'].' '.$recipient['e_middle_name'].' '.$recipient['e_last_name'].' '.$recipient['e_suffix_name'];
                                $details = ['email'=> $recipient['e_email'],
                                            'data' => $data];
                                
                                //SendCreateDocumentEmail::dispatchNow($details);
                                $emailJob = (new SendCreateDocumentEmail($details))->delay(Carbon::now('Asia/Manila')->addSeconds(10));
                                dispatch($emailJob);                    
                            }
                            return Redirect()->route('document.submitted.list')->with($notification);
                        }
                        //return Redirect()->back()->with($notification);
                    }

                    public function submitOrUpdateDraftDocument($request){ //SUBMIT with existing doc or Update Draft ( update view )
                        $email_notification = (isset($request['email_notification'][0]) ? 1 : 0);
                        $is_confidential = (isset($request['outer-group'][0]['is_confidential']) ? 1 : 0);
                        $is_hardcopy = (isset($request['is_hardcopy']) ? 1 : 0); 
                        $is_draft = ($request['save_btn'] == 'update_draft_val' ? 1 : 0); //UPDATE BLADE:update_draft_val, submit_draft_val
                        $document_id = $request['document_id'];
                        $old_path = $request['old_path'];
                        $old_path_orig = $request['old_path_orig'];
                        $barcode = $request['barcode'];
                        $title = $request['title'];
                        // if ($is_hardcopy){ $status = '1'; } //released thru routing
                        // else { $status = 'L'; } //sent released thru electronic
                    
                        //1. Update Document
                        $document_data = Document::where('document_id', $document_id)->first(); //document_id is the primary key, auto increment
                        $document_data->user_id = Auth::user()->id;
                        $document_data->title = $title;
                        $document_data->document_type_id = $request['document_type_id'];
                        $document_data->document_action_id = $request['document_action_id'];
                        $document_data->remarks = $request['remarks'];
                        $document_data->email_notification = $email_notification;
                        $document_data->is_confidential = $is_confidential;
                        $document_data->is_hardcopy = $is_hardcopy;
                        $document_data->is_draft = $is_draft;
                        //$document_data->courier_user_id = $request['courier_user_id'];
                        $document_data->updated_at = Carbon::now('Asia/Manila');
                        if(!$is_draft){ $document_data->submitted_at = Carbon::now('Asia/Manila'); }
                        //$withUpdateInDocument = $document_data->isDirty();
                        $document_data->save();

                        //2. Save route (after final submition only, except draft)
                        if(!$is_draft){
                            $route_id = $this->Route->insertRouteGetId(//$document_id, 
                                                                        Auth::user()->id,  //recipients
                                                                        $document_id,
                                                                        $title,
                                                                        $request['document_type_id'],
                                                                        $request['document_action_id'],
                                                                        'L',//$status, 
                                                                        Auth::user()->office_id, 
                                                                        Auth::user()->department_id,
                                                                        Auth::user()->section_id, 
                                                                        Carbon::now('Asia/Manila'));
                            // $route_arr = array(
                            //     'document_id' => $document_id,
                            //     'user_id' => Auth::user()->id, //recipients
                            //     'document_action_id' => $request['document_action_id'],
                            //     'status' => $status,  //Released
                            //     'office_id' => Auth::user()->office_id,
                            //     'department_id' => Auth::user()->department_id,
                            //     'section_id' => Auth::user()->section_id,
                            //     'created_at' => Carbon::now('Asia/Manila')
                            // );        
                            // $route_id = Route::insertGetId($route_arr);
                        }    

                        //3. Update recipients / users (Delete all records by document_id then insert)
                        //delete all existing user based on document_id
                        UserForwarded::where('document_id', $document_id)->delete();
                        //re-insert
                        foreach ($request['outer-group'][0]['forwarded_to_users'] as $key => $value){
                            //get recipient location details
                            $userLimitedDetails = $this->User->getUserLimitedDetails($value);   
                            
                            $recipient_arr = array(
                                'document_id' => $document_id,
                                'user_id' => $value,
                                'office_id' => $userLimitedDetails->office_id,
                                'department_id' => $userLimitedDetails->department_id,
                                'section_id' => $userLimitedDetails->section_id
                            );

                            $recipient_email_arr[] = array(
                                //for sending of notification, later use
                                'e_last_name' => $userLimitedDetails->last_name,
                                'e_first_name' => $userLimitedDetails->first_name,
                                'e_middle_name' => $userLimitedDetails->middle_name,
                                'e_suffix_name' => $userLimitedDetails->suffix_name,
                                'e_email' => $userLimitedDetails->email
                            );

                            UserForwarded::insert($recipient_arr);
                        }

                        //4. Update attachment  //attachedFile
                        //IF HAS NO FILE, EXIT, IF THERE IS A FILE, UPDATE THE DATA and DELETE THE FILE IN STORAGE
                        $file = $request->file('attachedFile');
                        //$withUpdateInAttachment = false;
                        //update file info
                                    $attachment_data = AttachedDocument::where('document_id', $document_id)->first();
                        $attachmentExtension = "";
                        $attachmentSize = "";
                        if ($file != null){ //with attached file
                            //since it has file, the new file has been selected
                            //check if has old file, delete actual
                            if ($old_path != ""){
                                //delete actual file
                                if (File::exists($old_path)) { unlink($old_path); }
                            }                            

                            $upload_path = 'storage/attachments/'; //public local folder
                            $attachmentNameOrig = $file->getClientOriginalName(); //attachment_name //visble to user
                            $attachmentExtension = $file->getClientOriginalExtension();
                            $attachmentSize = $file->getSize();
                            $attachmentNameInStorage = $barcode.'_'.$document_id.'_'.(Str::random(3)).'_'.preg_replace('/\s+/', '', $attachmentNameOrig); //attachment name in storage without whitespaces
                            $fullPathAndName = $upload_path.$attachmentNameInStorage; //save in Db at path attrib
                            $file->move(public_path().'/'.$upload_path, $attachmentNameInStorage); //move file to public folder //use move since direct saving to public folder
                        
                                    //$attachment_data->user_id = Auth::user()->id;
                                    $attachment_data->attachment_name = $attachmentNameOrig;
                                    $attachment_data->extension = $attachmentExtension;
                                    $attachment_data->size = $attachmentSize;
                                    $attachment_data->path = $fullPathAndName;
                        } elseif ($old_path == "" && $old_path_orig != "") { //the user has delete the old file, (current file is null)
                            //delete actual file
                            if (File::exists($old_path_orig)) { unlink($old_path_orig); }
                            //should not delete the attachment row since it is required and will be replaced.
                        }
                                    if ($attachment_data != null){ //if with existing record on file. 
                                        $attachment_data->external_link = $request['external_link'];
                                        $attachment_data->updated_at = Carbon::now('Asia/Manila');
                                        //$withUpdateInAttachment = $attachment_data->isDirty();
                                        $attachment_data->save();
                                    }    

                        //5. Save seen logs (after final submition only, except draft)
                        if(!$is_draft){
                            foreach ($request['outer-group'][0]['forwarded_to_users'] as $key => $value){ //recipients, to monitor their seen log
                                
                                $this->SeenLog->insertSeenLog($route_id,
                                                                $document_id,
                                                                $attachment_data->attached_document_id,
                                                                $request['document_action_id'],
                                                                Auth::user()->id,   //sender
                                                                $value, //recipient
                                                                $request['remarks'],   //reply
                                                                Carbon::now('Asia/Manila'));
                                // $seenlog_arr = array(
                                //     'route_id' => $route_id,
                                //     'attached_document_id' => $attachment_data->attached_document_id,
                                //     'document_id' => $document_id,
                                //     'sender_id' => Auth::user()->id,
                                //     'recipient_id' => $value,
                                //     'submitted_at' => Carbon::now('Asia/Manila')
                                // );        
                                // SeenLog::insert($seenlog_arr);
                            }
                        }              
    
                        $savingStat = ($request['save_btn'] == 'update_draft_val' ? "Draft document has been successfully updated." : "Document has been successfully sent.");
                        $notification = array(
                            'message' => $savingStat,
                            'alert-type' => 'success'
                        );

                        if ($is_draft){
                            return Redirect()->route('document.draft.list')->with($notification);
                        } else {
                            //SEND EMAIL NOTIFICATION
                            $data['barcode'] = $barcode;
                            $data['title'] = $title;
                            $data['hashed_docid'] = Hasher::encode($document_id);
                            $data['hashed_senderid'] = Hasher::encode(Auth::user()->id);
                            $data['sender_name'] = Auth::user()->first_name.' '.Auth::user()->middle_name.' '.Auth::user()->last_name.' '.Auth::user()->suffix_name;
                            $data['subject'] = "New Document: ".$title;
                            foreach ($recipient_email_arr as $key =>  $recipient) {                                
                                $data['recipient_name'] = $recipient['e_first_name'].' '.$recipient['e_middle_name'].' '.$recipient['e_last_name'].' '.$recipient['e_suffix_name'];
                                $details = ['email'=> $recipient['e_email'],
                                            'data' => $data];
                                
                                //SendCreateDocumentEmail::dispatchNow($details);
                                $emailJob = (new SendCreateDocumentEmail($details))->delay(Carbon::now('Asia/Manila')->addSeconds(10));
                                dispatch($emailJob);                    
                            }

                            return Redirect()->route('document.submitted.list')->with($notification);
                        }
                    }

    public function listDraft(){      
        abort_unless(\Gate::allows('search_document'), 403);
        $draft_docs = $this->Document->listOfCreatedDocumentsPerUserId(Auth::user()->id, true); //draft-true, submitted-false
        return view('document-management.draft-list', compact('draft_docs'));
    }

    public function deleteDraft($hashDocumentId){
        abort_unless(\Gate::allows('create_document'), 403);
        $document_id = Hasher::decode($hashDocumentId);
        Document::where('document_id', $document_id)->forceDelete();
        // Route::where('document_id', $document_id)->forceDelete(); //not exist here if draft
        // SeenLog::where('document_id', $document_id)->forceDelete(); //not exist here if draft
        UserForwarded::where('document_id', $document_id)->forceDelete();

        //delete file FIRST before the AttachedDocument DB from storage
        $document = AttachedDocument::where('document_id', $document_id)->first(); //get full path        
        $path = $document['path'];//set full path
        if (File::exists($path)) { unlink($path); }

        AttachedDocument::where('document_id', $document_id)->forceDelete();

        $notification = array(
            'message' => "Successfully deleted.",
            'alert-type' => 'success'
        );
        return Redirect()->back()->with($notification);
    }

    public function editDraft($hashDocumentId){
        abort_unless(\Gate::allows('create_document'), 403);
        $document_id = Hasher::decode($hashDocumentId);
        $document = $this->Document->getDocumentBasicDetailsByDocId($document_id);
        $document_types = $this->LibDocumentType->listOfDocumentTypesEnabledOnly();
        $document_actions = $this->LibDocumentAction->listOfDocumentActionsEnabledOnly();
        $doc_attached_fileInfo = $this->AttachedDocument->getFileInfoEnabledOnlyByDocId($document_id);
        $doc_forwardedtousersobject = $this->UserForwarded->getForwardedToUsersByDocId($document_id);
        $users = $this->User->listOfAllEnabledUsers(); 
        //object convert to multi array to use the in_array( array_column()) to search on the existence of user_id
        foreach($doc_forwardedtousersobject as $object){
            $doc_forwardedtousers[] = $object->toArray();
        }
        return view('document-management.edit', compact('document', 'document_types', 'document_actions', 'doc_attached_fileInfo', 'doc_forwardedtousers', 'users'));
    }

    public function listSubmitted(){
        abort_unless(\Gate::allows('search_document'), 403);
        $submitted_docs = $this->Document->listOfCreatedDocumentsPerUserId(Auth::user()->id, false); //draft-true, submitted-false
        return view('document-management.submitted-list', compact('submitted_docs'));
    }

    public function listReceived(){
        abort_unless(\Gate::allows('search_document'), 403);
        $received_docs = $this->SeenLog->getReceivedDocsByUserId(Auth::user()->id); //dd($received_docs);
        return view('document-management.received-list', compact('received_docs'));
    }

    public function listReleased(){
        abort_unless(\Gate::allows('search_document'), 403);
        $released_docs = $this->SeenLog->getReleasedDocsByUserId(Auth::user()->id); //dd($released_docs);
        return view('document-management.released-list', compact('released_docs'));
    }
    
    public function viewFullDocDetails($hashDocumentId){
        abort_unless(\Gate::allows('search_document'), 403);//router unable use this
        // abort_unless(\Gate::allows('search_document'), 403);
        $document_id = Hasher::decode($hashDocumentId);
        $recipientId = Auth::id();
        $this->SeenLog->tagDocumentAsSeen($document_id, $recipientId);
        $res = $this->Document->getDocumentFullDetailsByDocId($document_id);
        return json_encode($res);
    }

    // 02-20-2022
    // public function getFullDocDetailsForRoutingOnlyByBarcode($barcode){
    //     abort_unless(\Gate::allows('search_document'), 403); 
    //     $res = $this->Route->getDocumentFullDetailsForRoutingOnlyByBarcode($barcode);
    //     return json_encode($res);
    // }
    // public function receivedRoutedDocument($barcode){ 
    //     abort_unless(\Gate::allows('received_document'), 403); 
    //     //$res = $this->Route->getDocumentFullDetailsForRoutingOnlyByBarcode($barcode);
    //     //return json_encode($res);
    // }
    // public function releasedRoutedDocument($barcode){
    //     abort_unless(\Gate::allows('released_document'), 403); 
    //     //$res = $this->Route->getDocumentFullDetailsForRoutingOnlyByBarcode($barcode);
    //     //return json_encode($res);
    // }

    public function viewDocRouteDetails($hashDocumentId){
        abort_unless(\Gate::allows('search_document'), 403);//router unable use this
        // abort_unless(\Gate::allows('search_document'), 403);
        $document_id = Hasher::decode($hashDocumentId);
        $res = $this->Route->getDocumentRouteDetailsByDocId($document_id);
        return json_encode($res);
    }

    public function viewSeenLogsDetails($hashDocumentId){
        abort_unless(\Gate::allows('search_document'), 403); //router unable use this
        // abort_unless(\Gate::allows('search_document'), 403);
        $document_id = Hasher::decode($hashDocumentId);
        $res = $this->SeenLog->getUserSeenLogsDetailsByDocId($document_id);
        return json_encode($res);
    }

    public function reply($hashedDocId, $hashedSenderId){ 
        abort_unless(\Gate::allows('create_document'), 403); //prev commented 04-07-2022
        $docId = Hasher::decode($hashedDocId);
        $sender_id = Hasher::decode($hashedSenderId);
        $recipientId = Auth::id();
        $recipients = $this->UserForwarded->getForwardedToUsersDetailsByDocId($docId); //many
        $doc = $this->Document->getDocumentFullDetailsByDocId($docId); //one
        if (!$recipients->contains('user_id',Auth::id())){ //forwarded to
            if (!$doc->contains('user_id',Auth::id())){ //if not forwarded to, it must matched to a creator
                abort(402);
            }
        }  
        
        $reply = $this->SeenLog->getReceivedDocDetailsByDocId($docId); //$recipientId  //many //different route_id
        $this->SeenLog->tagDocumentAsSeen($docId, $recipientId);
        $document_actions = $this->LibDocumentAction->listOfDocumentActionsEnabledOnly();//listOfDocumentActions();
        //dd($reply);
        return view('document-management.reply', compact('doc', 'recipients', 'reply', 'document_actions', 'sender_id'));
    }

    public function replying(Request $request){
        //dd($request);
        //insert new route
        $route_id = $this->Route->insertRouteGetId($request['document_id'], 
                                                                Auth::user()->id,  //sender
                                                                '',//$document_id,
                                                                '',//$title,
                                                                '',//$request['document_type_id'],
                                                                $request['document_action_id'],
                                                                'L', 
                                                                Auth::user()->office_id, 
                                                                Auth::user()->department_id,
                                                                Auth::user()->section_id, 
                                                                Carbon::now('Asia/Manila'));
        //insert new
        $this->SeenLog->insertSeenLog($route_id,
                                        $request['document_id'],
                                        null, //attached_document_id
                                        $request['document_action_id'],         
                                        Auth::user()->id,  //sender
                                        $request['old_sender_id'],  //user_id sender
                                        $request['remarks'], //reply                        
                                        Carbon::now('Asia/Manila'));      
                                        
        //tagged replied_at to removed from the list of received docs and forwarded to documents released
        $this->SeenLog->taggedAsReplied($request['document_id'], Auth::user()->id);  //recipient/user      
                                        
        $notification = array(
            'message' => "Response has been successfully sent.",
            'alert-type' => 'success'
        );
        return Redirect()->route('document.received.list')->with($notification);
    }

    public function detailsByDocIdSenderId($hashedDocId){
        abort_unless(\Gate::allows('search_document'), 403);
        $docId = Hasher::decode($hashedDocId);
        $doc = $this->Document->getDocumentFullDetailsByDocId($docId); //one
        $recipients = $this->UserForwarded->getForwardedToUsersDetailsByDocId($docId); //many
        $reply = $this->SeenLog->getReceivedDocDetailsByDocId($docId); //$recipientId  //many //different route_id
        $document_actions = $this->LibDocumentAction->listOfDocumentActionsEnabledOnly();//listOfDocumentActions();
        //dd($reply);
        return view('document-management.details', compact('doc', 'recipients', 'reply', 'document_actions'));
    }

    //nag populate ung tag/recipuents sa tag
    
//details, route, status(seen, action)
    //$("#file-input").val(null);
}
