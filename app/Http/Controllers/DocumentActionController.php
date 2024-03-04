<?php

namespace App\Http\Controllers;

use App\LibDocumentAction;
use App\Services\Hasher;
use App\Http\Requests\DocumentActionStoreRequest;
use App\Http\Requests\DocumentActionUpdateRequest;
use Illuminate\Http\Request;

class DocumentActionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->lib_document_action = new LibDocumentAction();
    }

    public function list()
    {
        abort_unless(\Gate::allows('documentaction_view'), 403);

        //$doctypes =  $this->role->listOfRoles(\Gate::allows('su'));// su - if superus
        $docactions = $this->lib_document_action->listOfDocumentActions();
        return view('documentaction-management.documentactions', compact('docactions'));
    }

    public function store(DocumentActionStoreRequest $request)
    {
        abort_unless(\Gate::allows('documentaction_store'), 403);
        $this->lib_document_action->storeDocumentAction($request);
        $notification = array(
            'message' => 'Successfully saved.',
            'alert-type' => 'success'
        );

        return Redirect()->back()->with($notification);
    }

    public function edit($hid)
    {
        $id = Hasher::decode($hid);
        $docType = $this->lib_document_action->searchDocumenActionById($id);
        return json_encode($docType);
    }

    public function update(DocumentActionUpdateRequest $request)
    {
        abort_unless(\Gate::allows('documentaction_update'), 403);
        $id = Hasher::decode($request->hashid);
        $withUpdate = $this->lib_document_action->updateDocumentAction($id, $request);
        
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

    public function disable($hashid){
        abort_unless(\Gate::allows('documentaction_disable'), 403);
        $id = Hasher::decode($hashid);
        $this->lib_document_action->disableDocumentAction($id);
        $notification=array(
            'message'=>'Document action has been disabled.',
            'alert-type'=>'success'
        );
        return Redirect()->back()->with($notification);
    } 

    public function enable($hashid){
        abort_unless(\Gate::allows('documentaction_enable'), 403);
        $id = Hasher::decode($hashid);
        $this->lib_document_action->enableDocumentAction($id);
        $notification=array(
            'message'=>'Document action has been enabled.',
            'alert-type'=>'success'
        );
        return Redirect()->back()->with($notification);
    }
}
