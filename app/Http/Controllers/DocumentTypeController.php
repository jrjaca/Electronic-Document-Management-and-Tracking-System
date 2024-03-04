<?php

namespace App\Http\Controllers;

use App\LibDocumentType;
use App\Services\Hasher;
use App\Http\Requests\DocumentTypeStoreRequest;
use App\Http\Requests\DocumentTypeUpdateRequest;
use Picqer;

class DocumentTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->lib_document_type = new LibDocumentType();
        //$this->generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    }

    public function list()
    {
        abort_unless(\Gate::allows('documenttype_view'), 403);

        //$generator = new Picqer\Barcode\BarcodeGeneratorPNG();
        //$barcode =  $generator->getBarcode('0001245259636', $generator::TYPE_CODE_128);
        //dd($barcode);

        //$doctypes =  $this->role->listOfRoles(\Gate::allows('su'));// su - if superus
        $doctypes = $this->lib_document_type->listOfDocumentTypes();
        return view('documenttype-management.documenttypes', compact('doctypes'));
    }

    // public function create()
    // {
        
    // }

    public function store(DocumentTypeStoreRequest $request)
    {
        abort_unless(\Gate::allows('documenttype_store'), 403);
        $this->lib_document_type->storeDocumentType($request);
        $notification = array(
            'message' => 'Successfully saved.',
            'alert-type' => 'success'
        );

        return Redirect()->back()->with($notification);
    }

    public function edit($hid)
    {
        $id = Hasher::decode($hid);
        $docType = $this->lib_document_type->searchDocumenTypeById($id);
        return json_encode($docType);
    }

    public function update(DocumentTypeUpdateRequest $request)
    {
        abort_unless(\Gate::allows('documenttype_update'), 403);
        $id = Hasher::decode($request->hashid);
        $withUpdate = $this->lib_document_type->updateDocumentType($id, $request);
        
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
        abort_unless(\Gate::allows('documenttype_disable'), 403);
        $id = Hasher::decode($hashid);
        $this->lib_document_type->disableDocumentType($id);
        $notification=array(
            'message'=>'Document type has been disabled.',
            'alert-type'=>'success'
        );
        return Redirect()->back()->with($notification);
    }     

    public function enable($hashid){
        abort_unless(\Gate::allows('documenttype_enable'), 403);
        $id = Hasher::decode($hashid);
        $this->lib_document_type->enableDocumentType($id);
        $notification=array(
            'message'=>'Document type has been enabled.',
            'alert-type'=>'success'
        );
        return Redirect()->back()->with($notification);
    }
}
