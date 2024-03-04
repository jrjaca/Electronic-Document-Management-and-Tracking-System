<?php

namespace App;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LibDocumentType extends Model
{
    use SoftDeletes;

    public function listOfDocumentTypes(){ ////softdeleted included, DocumentTypeController, list function
        return DB::table('lib_document_types')
            ->orderBy('title', 'ASC')
            ->orderBy('description', 'ASC')
            ->get();
    }

    //softdeleted excluded, DocumentActionController create, editDraft. PublishController create function
    //PublishController editPublishedDoc, createPublishedRoutedDoc function
    //HomeController advancedSearch function
    //RouteController create, editDraft function
    public function listOfDocumentTypesEnabledOnly(){ 
        return DB::table('lib_document_types')
            ->orderBy('title', 'ASC')
            ->orderBy('description', 'ASC')
            ->where('lib_document_types.deleted_at', null)
            ->get();
    }

    public function searchDocumenTypeById($id) //DocumentTypeController, edit function
    {
        return DB::table('lib_document_types')->where('id', $id)->get();
    }

    public function storeDocumentType($request) //DocumentTypeController, store function
    {
        $res = new LibDocumentType();
        $res->title = $request->title;
        $res->description = $request->description;
        $res->save();
    }

    public function updateDocumentType($id, $request) //DocumentTypeController, update function
    {
        $res = LibDocumentType::where('id', '=', $id)->first(); //softdeleted will be not found
        if ($res === null){ //if not exist or deleted
            return false;
        } 

        $res->title = $request->title;
        $res->description = $request->description;
        $withUpdate = $res->isDirty();
        $res->save();

        if ($withUpdate) { return true; }
        else { return false; }
    }

    public function disableDocumentType($id) //DocumentTypeController, disable function
    {
        LibDocumentType::find($id)->delete();
    }

    public function enableDocumentType($id) //DocumentTypeController, enable function
    {
        LibDocumentType::withTrashed()->where('id', $id)->restore();
    }
    
}
