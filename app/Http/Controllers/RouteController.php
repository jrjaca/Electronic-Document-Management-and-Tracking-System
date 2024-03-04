<?php

namespace App\Http\Controllers;

use App\User;
use App\Jobs\SendEmail;
use App\LibDocumentType;
use App\Services\Hasher;
use App\Mail\GeneralMail;
use App\LibDocumentAction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Model\Document\Route;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class RouteController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->Route = new Route();
        $this->LibDocumentType = new LibDocumentType();
        $this->LibDocumentAction = new LibDocumentAction();
        // $this->Document = new Document();
        // $this->UserForwarded = new UserForwarded();
        // $this->AttachedDocument = new AttachedDocument();
        $this->User = new User();
        // $this->SeenLog = new SeenLog();
    }

    public function index(){//
        abort_unless(\Gate::allows('track_document'), 403);
        return view('document-route.index');
    }

    public function create(){//
        abort_unless(\Gate::allows('generate_barcode'), 403);
        $currentDateTime = Carbon::now('Asia/Manila')->format('ymd-His');
        $raw_code = $currentDateTime.'-'.substr(Auth::user()->username, -3);
        $document_types = $this->LibDocumentType->listOfDocumentTypesEnabledOnly();
        $document_actions = $this->LibDocumentAction->listOfDocumentActionsEnabledOnly();
        $users = $this->User->listOfAllEnabledUsers(); 
        return view('document-route.create', compact('raw_code', 'document_types', 'document_actions', 'users'));
    }
    public function editDraft($hashedRouteId){
        abort_unless(\Gate::allows('generate_barcode'), 403);
        $route_id = Hasher::decode($hashedRouteId);
        $route = $this->Route->getRouteDocBasicDetailsByRouteId($route_id);
        $document_types = $this->LibDocumentType->listOfDocumentTypesEnabledOnly();
        $document_actions = $this->LibDocumentAction->listOfDocumentActionsEnabledOnly();
        $users = $this->User->listOfAllEnabledUsers(); 
        return view('document-route.edit', compact('route', 'document_types', 'document_actions', 'users'));
    }
            function submitCreate(Request $request){ 
                abort_unless(\Gate::allows('generate_barcode'), 403);
                $btn = $request['save_btn'];
                //----- start -validation ----//
                $rules = array(
                    'title' => 'required|string',
                    'document_type_id' => 'required',
                    'document_action_id' => 'required',
                    //'outer-group.0.forwarded_to_users' => 'required', //array
                    //'attachedFile' => ['required_without:is_hardcopy','max:10999', 'mimes:jpeg,png,pdf,doc,docx,xls,xlsx,ppt,pptx'], //file //if is_hardcopy is false, require attachment
                    'attachedFile' => ['max:10999', 'mimes:jpeg,png,pdf,doc,docx,xls,xlsx,ppt,pptx'], 
                );    
                $messages = array(
                    'title.required' => 'Document name is required.',
                    'title.string' => 'Document name must be a string.',
                    'document_type_id.required' => 'Document type is required.',
                    'document_action_id.required' => 'Purpose of document is required (For).',
                    //'outer-group.0.forwarded_to_users.required' => 'Provide at least one recipient/user (Forward To).',
                    //'attachedFile.required_without' => 'Attach File is required.',
                    'attachedFile.max' => 'File size must not be greater than 10 MB.',
                    'attachedFile.mimes' => 'Attached file must be of the following format: jpeg, png, pdf, doc, docx, xls, xlsx, ppt, pptx.',
                );

                $validator = Validator::make( $request->all(), $rules, $messages );                
                if ($validator->fails()){
                    return Redirect()->back()->withInput()->withErrors($validator->messages()->get('*')); 
                }
                //----- end -validation ----//

                if ($btn == "submit_val" || $btn == "draft_val") { //SUBMIT and DRAFT: new record ( create view )
                    $res = $this->releasedOrDraftRoute($request);
                } elseif ($btn == "submit_draft_val" || $btn == "update_draft_val") { //SUBMIT with existing doc or Update Draft ( update view )
                    $res = $this->releasedOrUpdateDraftRoute($request);
                } 

                return $res; // Final notification
            }
                    public function releasedOrDraftRoute($request){
                        $email_notification = (isset($request['email_notification'][0]) ? 1 : 0);

                        // $is_draft = ($request['save_btn'] == 'draft_val' ? 1 : 0); //CREATE BLADE:draft_val, submit_val
                        // if(!$is_draft) { $routedAt = Carbon::now('Asia/Manila'); }
                        // else           { $routedAt = null; }
                        if ($request['save_btn'] == 'draft_val'){
                            //$is_draft = true;
                            $finalizedAt = null;
                            $status = 'D'; //draft
                        } else {
                            //$is_draft = false;
                            $status = 'F'; //finalized
                            $finalizedAt = Carbon::now('Asia/Manila'); 
                        }

                        //attachedFile
                        $file = $request->file('attachedFile');
                        $attachmentNameOrig = "";
                        $fullPathAndName = "";
                        $attachmentExtension = "";
                        $attachmentSize = "";
                        if ($file != null){ //with attached file
                            $upload_path = 'storage/route/'; //public local folder
                            $attachmentNameOrig = $file->getClientOriginalName(); //attachment_name //visble to user
                            $attachmentExtension = $file->getClientOriginalExtension();
                            $attachmentSize = $file->getSize();
                            $attachmentNameInStorage = $request['tracking_no'].'_'.(Str::random(3)).'_'.preg_replace('/\s+/', '', $attachmentNameOrig); //attachment name in storage without whitespaces
                            $fullPathAndName = $upload_path.$attachmentNameInStorage; //save in Db at path attrib
                            $file->move(public_path().'/'.$upload_path, $attachmentNameInStorage); //move file to public folder //use move since direct saving to public folder
                        }       
                        
                        $route_arr = array(  //submit or draft
                            //'is_draft' => $is_draft,
                            'status' => $status, 
                            'tracking_no' => $request['tracking_no'],
                            'user_id' => Auth::user()->id,
                            'title' => $request['title'], 
                            'document_type_id' => $request['document_type_id'],
                            'document_action_id' => $request['document_action_id'],
                            'remarks' => $request['remarks'],
                            'email_notification' => $email_notification,
                            'courier_user_id' => $request['courier_user_id'],
                            'office_id' => Auth::user()->office_id,
                            'department_id' => Auth::user()->department_id,
                            'section_id' => Auth::user()->section_id,
                            'attachment_name' => $attachmentNameOrig,
                            'extension' => $attachmentExtension,
                            'size' => $attachmentSize,
                            'path' => $fullPathAndName,
                            'finalized_at' => $finalizedAt,
                            'created_at' => Carbon::now('Asia/Manila'),
                        );        
                        Route::insert($route_arr);

                        if ($status == 'F'){ //if finalized, save another same row but as RELEASED
                            $route_arr = array(  //submit or draft
                                //'is_draft' => $is_draft,
                                'status' => 'R', //released 
                                'tracking_no' => $request['tracking_no'],
                                'user_id' => Auth::user()->id,
                                'title' => $request['title'], 
                                'document_type_id' => $request['document_type_id'],
                                'document_action_id' => $request['document_action_id'],
                                'remarks' => $request['remarks'],
                                'email_notification' => $email_notification,
                                'courier_user_id' => $request['courier_user_id'],
                                'office_id' => Auth::user()->office_id,
                                'department_id' => Auth::user()->department_id,
                                'section_id' => Auth::user()->section_id,
                                'attachment_name' => $attachmentNameOrig,
                                'extension' => $attachmentExtension,
                                'size' => $attachmentSize,
                                'path' => $fullPathAndName,
                                'routed_at' => Carbon::now('Asia/Manila'),
                                'created_at' => Carbon::now('Asia/Manila'),
                            );        
                            Route::insert($route_arr);
                        }

                        $savingStat = ($request['save_btn'] == 'draft_val' ? "Draft route document has been successfully saved." : "Route document has been successfully released.");
                        $notification = array(
                            'message' => $savingStat,
                            'alert-type' => 'success'
                        );

                        if ($status == 'D'){
                            return Redirect()->route('route.draft.list')->with('success', $savingStat)->with($notification);
                        } else {
                            return Redirect()->route('route.finalized.list')->with('success', $savingStat)->with($notification);
                            //return Redirect()->back()->with('success', $savingStat)->with($notification);
                        }

                        //return Redirect()->back()->with($notification);
                        // $notification = array(
                        //     'message' => "Successfully saved!",
                        //     'alert-type' => 'success'
                        // );
                        // return Redirect()->back()->with($notification);
                    }

                    public function releasedOrUpdateDraftRoute($request){ //SUBMIT with existing doc or Update Draft ( update view )
                        
                        $email_notification = (isset($request['email_notification'][0]) ? 1 : 0);
                        $route_id = $request['route_id'];
                        $old_path = $request['old_path'];
                        $old_path_orig = $request['old_path_orig'];

                        if ($request['save_btn'] == 'update_draft_val'){
                            //$is_draft = true;
                            //$status = 'C';
                            $status = 'D'; //draft
                            $finalizedAt = null;
                        } else {
                            //$is_draft = false;
                            //$status = 'R';
                            $status = 'F'; //finalized
                            $finalizedAt = Carbon::now('Asia/Manila'); 
                        }

                        $route_data = Route::where('route_id', $route_id)->first(); 
                        //$route_data->is_draft = $is_draft;
                        $route_data->status = $status;
                        $route_data->tracking_no = $request['tracking_no'];
                        $route_data->user_id = Auth::user()->id;
                        $route_data->title = $request['title'];
                        $route_data->document_type_id = $request['document_type_id'];
                        $route_data->document_action_id = $request['document_action_id'];
                        $route_data->remarks = $request['remarks'];
                        $route_data->email_notification = $email_notification;
                        $route_data->courier_user_id = $request['courier_user_id'];
                        $route_data->office_id = Auth::user()->office_id;
                        $route_data->department_id = Auth::user()->department_id;
                        $route_data->section_id = Auth::user()->section_id;
                        
                        //attachedFile
                        $file = $request->file('attachedFile');  
                        $attachmentNameOrig = $route_data->attachment_name;
                        $fullPathAndName = $route_data->path;
                        $attachmentExtension = $route_data->extension;
                        $attachmentSize = $route_data->size;

                        if ($file != null){ //with attached file
                            //since it has file, the new file has been selected
                            //check if has old file, delete actual
                            if ($old_path != ""){
                                //delete actual file
                                if (File::exists($old_path)) { unlink($old_path); }
                            }                            

                            $upload_path = 'storage/route/'; //public local folder
                            $attachmentNameOrig = $file->getClientOriginalName(); //attachment_name //visble to user
                            $attachmentExtension = $file->getClientOriginalExtension();
                            $attachmentSize = $file->getSize();
                            $attachmentNameInStorage = $request['tracking_no'].'_'.(Str::random(3)).'_'.preg_replace('/\s+/', '', $attachmentNameOrig); //attachment name in storage without whitespaces
                            $fullPathAndName = $upload_path.$attachmentNameInStorage; //save in Db at path attrib
                            $file->move(public_path().'/'.$upload_path, $attachmentNameInStorage); //move file to public folder //use move since direct saving to public folder
                                $route_data->attachment_name = $attachmentNameOrig;
                                $route_data->extension = $attachmentExtension;
                                $route_data->size = $attachmentSize;
                                $route_data->path = $fullPathAndName;
                        } elseif ($old_path == "" && $old_path_orig != "") { //the user has delete the old file, (current file is null)
                            //delete actual file
                            if (File::exists($old_path_orig)) { unlink($old_path_orig); }
                            $route_data->attachment_name = '';
                            $route_data->extension = '';
                            $route_data->size = '';
                            $route_data->path = '';
                        }

                        $route_data->finalized_at = $finalizedAt;
                        $route_data->save();

                        if ($status == 'F'){ //if finalized, save another same row but as RELEASED
                            $route_arr = array(  //submit or draft
                                //'is_draft' => $is_draft,
                                'status' => 'R', //released 
                                'tracking_no' => $request['tracking_no'],
                                'user_id' => Auth::user()->id,
                                'title' => $request['title'], 
                                'document_type_id' => $request['document_type_id'],
                                'document_action_id' => $request['document_action_id'],
                                'remarks' => $request['remarks'],
                                'email_notification' => $email_notification,
                                'courier_user_id' => $request['courier_user_id'],
                                'office_id' => Auth::user()->office_id,
                                'department_id' => Auth::user()->department_id,
                                'section_id' => Auth::user()->section_id,
                                'attachment_name' => $attachmentNameOrig,
                                'extension' => $attachmentExtension,
                                'size' => $attachmentSize,
                                'path' => $fullPathAndName,
                                'routed_at' => Carbon::now('Asia/Manila'),
                                'created_at' => Carbon::now('Asia/Manila'),
                            );        
                            Route::insert($route_arr);
                        }

                        $savingStat = ($request['save_btn'] == 'update_draft_val' ? "Draft route document has been successfully updated." : "Route document has been successfully released.");
                        $notification = array(
                            'message' => $savingStat,
                            'alert-type' => 'success'
                        );

                        if ($status == 'D'){
                            return Redirect()->route('route.draft.list')->with('success', $savingStat)->with($notification);
                        } else {
                            return Redirect()->route('route.finalized.list')->with('success', $savingStat)->with($notification);
                        }
                        //return Redirect()->back()->with('success', $savingStat)->with($notification);
                    }

    public function viewFullDocDetails($hashedRouteId){ //
        abort_unless(\Gate::allows('track_document'), 403);
        $route_id = Hasher::decode($hashedRouteId);
        $res = $this->Route->getRouteDocumentFullDetailsByRouteId($route_id);
        return json_encode($res);
    }

    public function deleteDraft($hashedRouteId){  
        abort_unless(\Gate::allows('generate_barcode'), 403); 
        $route_id = Hasher::decode($hashedRouteId);

        //detached document
        //delete file FIRST before the AttachedDocument DB from storage
        $info = Route::where('route_id', $route_id)->first(); //get full path   
        $path = $info['path'];//set full path
        if (File::exists($path)) { unlink($path); }

        //delete record
        Route::where('route_id', $route_id)->forceDelete();

        $msg = "Draft ".$info['title']." with tracking number ".$info['tracking_no']." has been successfully deleted.";
        $notification = array(
            'message' => $msg,
            'alert-type' => 'success'
        );
        return Redirect()->back()->with('success', $msg)->with($notification);
    }

    public function draft(){ //
        abort_unless(\Gate::allows('generate_barcode'), 403);
        $userId = Auth::user()->id;
        $routes = $this->Route->getAllRouteDocBasicDetailsByUserIdStatusDFOnly($userId, 'D'); 
        return view('document-route.draft-list', compact('routes'));
    }

    public function finalized(){//user's created routing doc
        abort_unless(\Gate::allows('generate_barcode'), 403);
        $userId = Auth::user()->id;
        $routes = $this->Route->getAllRouteDocBasicDetailsByUserIdStatusDFOnly($userId, 'F'); 
        return view('document-route.finalized-list', compact('routes'));
    }
    
    public function received(){
        abort_unless(\Gate::allows('received_document'), 403);
        $userId = Auth::user()->id;
        $routes = $this->Route->getAllRouteDocBasicDetailsByUserIdStatusRCOnly($userId, 'C'); 
        return view('document-route.received-list', compact('routes'));
    }

    public function released(){ //
        abort_unless(\Gate::allows('released_document'), 403);
        $userId = Auth::user()->id;
        $routes = $this->Route->getAllRouteDocBasicDetailsByUserIdStatusRCOnly($userId, 'R'); 
        return view('document-route.released-list', compact('routes'));
    }

    public function terminal(){
        abort_unless(\Gate::allows('tag_as_terminal'), 403);
        $userId = Auth::user()->id;
        $routes = $this->Route->getAllRouteDocBasicDetailsByUserIdStatusTerminalOnly($userId); //status is F and terminal_at is not null
        // dd($routes);
        return view('document-route.terminal-list', compact('routes'));
    }

    public function searchByTrackingNo(Request $request){
        abort_unless(\Gate::allows('track_document'), 403);
        //----- start -validation ----//
        $rules = array(
            'searching_tr' => 'required|string'
        );    
        $messages = array(
            'searching_tr.required' => 'Tracking number is required.',
            'searching_tr.string' => 'Tracking number must be a string.'
        );

        $validator = Validator::make( $request->all(), $rules, $messages );                
        if ($validator->fails()){
            return Redirect()->back()->withInput()->withErrors($validator->messages()->get('*')); 
        }
        //----- end -validation ----//

        $tracking_no = $request['searching_tr'];
        $routes = $this->Route->getDocumentRoutesByTrackingNo($tracking_no);

        if ($routes->isEmpty()){
            $msg = "Tracking number ".$tracking_no." does not exist.";
            $notification = array(
                'message' => $msg,
                'alert-type' => 'info'
            );
            return Redirect()->back()->with('info', $msg)->with($notification);
        } else {
            return view('document-route.details', compact('routes'));  //searchByEncryptedId() and searchByTrackingNo()
        }
    }

    //view route doc from email sent
    public function searchByEncryptedId($encId){
        $tracking_no = Crypt::decrypt($encId);
        $routes = $this->Route->getDocumentRoutesByTrackingNo($tracking_no);
        return view('document-route.details', compact('routes')); //searchByEncryptedId() and searchByTrackingNo()
    }

    // public function createReleased($trackingNo){
    //     abort_unless(\Gate::allows('released_document'), 403);
    //     return view('document-route.create-released');
    // }
            
    //full details of document and route
    //Home index, Route Management index
    public function routeTrail($tracking_no){
        abort_unless(\Gate::allows('track_document'), 403); //create separate access for route trail?
        //$tracking_no = Hasher::decode($hashedTrackNo);
        $res = $this->Route->getDocumentRoutesByTrackingNo($tracking_no);
        return json_encode($res);
    }
    
    //Home index, Route Management index
    public function submitReceiving(Request $request){
        abort_unless(\Gate::allows('received_document'), 403);
        //----- start -validation ----//
        $rules = array(
            'receiving_tr' => 'required|string'
        );    
        $messages = array(
            'receiving_tr.required' => 'Tracking number is required.',
            'receiving_tr.string' => 'Tracking number must be a string.'
        );

        $validator = Validator::make( $request->all(), $rules, $messages );                
        if ($validator->fails()){
            return Redirect()->back()->withInput()->withErrors($validator->messages()->get('*')); 
        }
        //----- end -validation ----//

        $tracking_no = $request['receiving_tr'];
        $info = $this->Route->getLastRouteBasicInfoByTrackingNo($tracking_no);

        if ($info == null){
            $msg = "Tracking number ".$tracking_no." does not exist.";
            $notification = array(
                'message' => $msg,
                'alert-type' => 'info'
            );
            return Redirect()->back()->with('info', $msg)->with($notification);
        } elseif ($info->terminal_at != null){//terminal
            $msg = "Tracking number ".$tracking_no." is a terminal document.";
            $notification = array(
                'message' => $msg,
                'alert-type' => 'info'
            );
            return Redirect()->back()->with('info', $msg)->with($notification);
        } elseif ($info->status == 'D'){//Draft
            $msg = "Tracking number ".$tracking_no." is a draft document. Need to finalized first.";
            $notification = array(
                'message' => $msg,
                'alert-type' => 'info'
            );
            return Redirect()->back()->with('info', $msg)->with($notification);

        // } elseif  NEED TO CHECK IF ALREADY RECEIVED
        } else {

            //update the Received_at of the last receiver for TAT
            $this->Route->UpdateReceivedAtByTrackingNo($tracking_no);

            //before insert
            $route_arr = array(  
                'status' => 'C', //Received 
                'tracking_no' => $tracking_no,
                'user_id' => Auth::user()->id,
                'title' => $info->title, 
                'document_type_id' => $info->document_type_id,
                'document_action_id' => $info->document_action_id,
                'remarks' => $info->remarks,
                'email_notification' => $info->email_notification,
                'courier_user_id' => $info->courier_user_id,
                'office_id' => Auth::user()->office_id,
                'department_id' => Auth::user()->department_id,
                'section_id' => Auth::user()->section_id,
                'attachment_name' => $info->attachment_name,
                'extension' => $info->extension,
                'size' => $info->size,
                'path' => $info->path,
                'routed_at' => Carbon::now('Asia/Manila'),
                'created_at' => Carbon::now('Asia/Manila'),
            );        
            Route::insert($route_arr);
            /////IF UNSUCCESSFULL, HOW TO ROLLBACK the UpdateReceivedAtByTrackingNo() update??

            //-------Send email-------//
            $this->emailRouteStatus('C', $info);
            //-------Send email-------//

            //Mail::to($userEmail)->queue(new invoiceMail($data)); //QUEUE
            //Mail::to($userEmail)->later($when, new invoiceMail($data)); //DELAYED
            /*
            Mail::to($request->user())
                ->cc($moreUsers)
                ->bcc($evenMoreUsers)
                ->send(new OrderShipped($order)); 
                loop
                foreach (['taylor@example.com', 'dries@example.com'] as $recipient) {
                    Mail::to($recipient)->send(new OrderShipped($order));
                }
            */
            //-------Send email-------//


            $msg = "Document with tracking number ".$tracking_no." has been successfully received by your office.";
            $notification = array(
                'message' => $msg,
                'alert-type' => 'success'
            );
            return Redirect()->back()->with('success', $msg)->with($notification);
        }

    }

    // Home index, Route Management index
    public function createReleased(Request $request){
        abort_unless(\Gate::allows('released_document'), 403);
        //----- start -validation ----//
        $rules = array(
            'releasing_tr' => 'required|string'
        );    
        $messages = array(
            'releasing_tr.required' => 'Tracking number is required.',
            'releasing_tr.string' => 'Tracking number must be a string.'
        );

        $validator = Validator::make( $request->all(), $rules, $messages );                
        if ($validator->fails()){
            return Redirect()->back()->withInput()->withErrors($validator->messages()->get('*')); 
        }
        //----- end -validation ----//

        $tracking_no = $request['releasing_tr'];
        $route = $this->Route->getLastRouteBasicInfoByTrackingNo($tracking_no);

        if ($route == null){
            $msg = "Tracking number ".$tracking_no." does not exist.";
            $notification = array(
                'message' => $msg,
                'alert-type' => 'info'
            );
            return Redirect()->back()->with('info', $msg)->with($notification);
        } elseif ($route->terminal_at != null){//terminal
            $msg = "Tracking number ".$tracking_no." is a terminal document.";
            $notification = array(
                'message' => $msg,
                'alert-type' => 'info'
            );
            return Redirect()->back()->with('info', $msg)->with($notification);
        } elseif ($route->status == 'D'){//Draft
            $msg = "Tracking number ".$tracking_no." is a draft document. Need to finalized first.";
            $notification = array(
                'message' => $msg,
                'alert-type' => 'info'
            );
            return Redirect()->back()->with('info', $msg)->with($notification);
        } else {
            $document_actions = $this->LibDocumentAction->listOfDocumentActionsEnabledOnly();
            $users = $this->User->listOfAllEnabledUsers(); 
            return view('document-route.create-released', compact('tracking_no', 'document_actions', 'users'));
        }
        
    }
            public function submitReleasing(Request $request){
                abort_unless(\Gate::allows('released_document'), 403);
                //----- start -validation ----//
                // $rules = array(
                //     'releasing_tr' => 'required|string'
                // );    
                // $messages = array(
                //     'releasing_tr.required' => 'Tracking number is required.',
                //     'releasing_tr.string' => 'Tracking number must be a string.'
                // );

                // $validator = Validator::make( $request->all(), $rules, $messages );                
                // if ($validator->fails()){
                //     return Redirect()->back()->withInput()->withErrors($validator->messages()->get('*')); 
                // }
                //----- end -validation ----//

                $tracking_no = $request['tracking_no'];
                $route = $this->Route->getLastRouteBasicInfoByTrackingNo($tracking_no);

                if ($route == null){
                    $msg = "Tracking number ".$tracking_no." does not exist.";
                    $notification = array(
                        'message' => $msg,
                        'alert-type' => 'info'
                    );
                    return Redirect()->back()->with('info', $msg)->with($notification);
                } elseif ($route->terminal_at != null){//terminal
                    $msg = "Tracking number ".$tracking_no." is a terminal document.";
                    $notification = array(
                        'message' => $msg,
                        'alert-type' => 'info'
                    );
                    return Redirect()->back()->with('info', $msg)->with($notification);
                } elseif ($route->status == 'D'){//Draft
                    $msg = "Tracking number ".$tracking_no." is a draft document. Need to finalized first.";
                    $notification = array(
                        'message' => $msg,
                        'alert-type' => 'info'
                    );
                    return Redirect()->back()->with('info', $msg)->with($notification);
                } else { //release
                    //update the Received_at of the last receiver for TAT
                    $this->Route->UpdateReceivedAtByTrackingNo($tracking_no);

                    $email_notification = (isset($request['email_notification'][0]) ? 1 : 0);
                    $route_arr = array(  
                        'status' => 'R', //Released 
                        'tracking_no' => $tracking_no,
                        'user_id' => Auth::user()->id,
                        'title' => $route->title, 
                        'document_type_id' => $route->document_type_id,
                        'document_action_id' => $request['document_action_id'],
                        'remarks' => $request['remarks'],
                        'email_notification' => $email_notification,
                        'courier_user_id' => $request['courier_user_id'],
                        'office_id' => Auth::user()->office_id,
                        'department_id' => Auth::user()->department_id,
                        'section_id' => Auth::user()->section_id,
                        //'attachment_name' => $info->attachment_name,
                        //'extension' => $info->extension,
                        //'size' => $info->size,
                        //'path' => $info->path,
                        'routed_at' => Carbon::now('Asia/Manila'),
                        'created_at' => Carbon::now('Asia/Manila'),
                    );        
                    Route::insert($route_arr);
                
                    //-------Send email-------//
                    $this->emailRouteStatus('R', $route);
                    //-------Send email-------//

                    $msg = "The document with tracking number ".$tracking_no." has been successfully released.";
                    $notification = array(
                        'message' => $msg,
                        'alert-type' => 'success'
                    );
                    return Redirect()->route('route.index')->with('success', $msg)->with($notification);
                }

                
            }
        
    //tagAsTerminalPost function. Home index, Route Management index
    public function tagAsTerminal($info){
        abort_unless(\Gate::allows('tag_as_terminal'), 403); //all R and C terminal_at will update the date
        
        //update the Received_at of the last receiver for TAT
        $this->Route->UpdateReceivedAtByTrackingNo($info->tracking_no);
        
        Route::where('tracking_no', $info->tracking_no)
                    ->update([
                        'terminal_at' => Carbon::now('Asia/Manila'),
                        //'received_at' => Carbon::now('Asia/Manila'),
                        'terminal_user_id' => Auth::user()->id,
                        'terminal_office_id' => Auth::user()->office_id,
                        'terminal_department_id' => Auth::user()->department_id,
                        'terminal_section_id' => Auth::user()->section_id
                    ]);

        //-------Send email-------//
        $this->emailRouteStatus('T', $info);
        //-------Send email-------//

        $msg = "Document with tracking number ".$info->tracking_no." has been successfully tagged as terminal.";
        $notification = array(
            'message' => $msg,
            'alert-type' => 'success'
        );
        return Redirect()->back()->with('success', $msg)->with($notification);
    }

    public function tagAsTerminalPost(Request $request){
        abort_unless(\Gate::allows('tag_as_terminal'), 403); //all R and C terminal_at will update the date

        //----- start -validation ----//
        $rules = array(
            'terminal_tr' => 'required|string'
        );    
        $messages = array(
            'terminal_tr.required' => 'Tracking number is required.',
            'terminal_tr.string' => 'Tracking number must be a string.'
        );

        $validator = Validator::make( $request->all(), $rules, $messages );                
        if ($validator->fails()){
            return Redirect()->back()->withInput()->withErrors($validator->messages()->get('*')); 
        }
        //----- end -validation ----//

        $tracking_no = $request['terminal_tr'];
        $info = $this->Route->getLastRouteBasicInfoByTrackingNo($tracking_no);
    
        if ($info == null){
            $msg = "Tracking number ".$tracking_no." does not exist.";
            $notification = array(
                'message' => $msg,
                'alert-type' => 'info'
            );
        } elseif ($info->terminal_at != null) { //already taggeda sa terminal document
            $msg = "Document with tracking number ".$tracking_no." has already been tagged as terminal.";
            $notification = array(
                'message' => $msg,
                'alert-type' => 'info'
            );
        } elseif ($info->status == 'D'){//Draft
            $msg = "Tracking number ".$tracking_no." is a draft document. Need to finalized first.";
            $notification = array(
                'message' => $msg,
                'alert-type' => 'info'
            );
            return Redirect()->back()->with('info', $msg)->with($notification);
        } else { return $this->tagAsTerminal($info); }        
        return Redirect()->back()->with('error', $msg)->with($notification);
    }

    public function emailRouteStatus($stat, $info){        
        if ($stat == 'R'){   
            $data['caller_from'] = 'RouteReleased';  //RouteReceived/RouteReleased/RouteTerminal unique per caller since it uses common function from App\Mail\GeeralMail.php
            $data['subject'] = "Routing: Released Document ".$info->title;
            $data['body_status'] = "RELEASED";
            $data['title'] = $info->title;
            $data['tracking_no'] = $info->tracking_no;     
            $data['processed_by'] = Auth::user()->first_name.' '.Auth::user()->last_name.' ('.Auth::user()->username.')'; 
            $data['encrypted_str'] = Crypt::encrypt($info->tracking_no); 
            //$when = now()->addMinutes(1); //FOR DELAYED SENDING
        } elseif ($stat == 'C'){     
            $data['caller_from'] = 'RouteReceived';
            $data['subject'] = "Routing: Received Document ".$info->title;
            $data['body_status'] = "RECEIVED";
            $data['title'] = $info->title;
            $data['tracking_no'] = $info->tracking_no;     
            $data['processed_by'] = Auth::user()->first_name.' '.Auth::user()->last_name.' ('.Auth::user()->username.')'; 
            $data['encrypted_str'] = Crypt::encrypt($info->tracking_no);   
        } elseif ($stat == 'T'){
            $data['caller_from'] = 'RouteTerminal'; 
            $data['subject'] = "Routing: Terminal Document ".$info->title;
            $data['body_status'] = "set to TERMINAL";
            $data['title'] = $info->title;
            $data['tracking_no'] = $info->tracking_no;     
            $data['processed_by'] = Auth::user()->first_name.' '.Auth::user()->last_name.' ('.Auth::user()->username.')'; 
            $data['encrypted_str'] = Crypt::encrypt($info->tracking_no); 
        }
            
        $recipients = $this->Route->GetRecepientsEmailRouteByTrackingNo($info->tracking_no);
        foreach ($recipients as $recipient) {
            $data['recipient_name'] = $recipient->usr_first_name.' '.$recipient->usr_last_name;
            
            // //delayed sending to 1 minute
            // $when = now()->addMinutes(1);
            // Mail::to($recipient->usr_email)
            //         //->send(new GeneralMail($data));  //email to user, check email sender use
            //         //->later(now()->addMinutes(1), new GeneralMail($data));    
            //         //->queue(new GeneralMail($data));     
            //         ->later($when, new GeneralMail($data));
                    
            $details = ['email'=> $recipient->usr_email,
                        'data' => $data];
            //SendEmail::dispatchNow($details); //passed to SendMail.php / if you want to send w/o a que but TOO SLOW FOR USER SIDE
            //SendEmail::dispatch($details); //passed to SendMail.php / with que but required to run php artisan queue:work --queue=high,default                
            $emailJob = (new SendEmail($details))->delay(Carbon::now('Asia/Manila')->addSeconds(10));
            dispatch($emailJob);
        }
    }

}
