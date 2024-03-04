<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LibDocumentAction extends Model
{
    use SoftDeletes;

    //softdeleted included, DocumentActionController, list function
    public function listOfDocumentActions(){  
        return DB::table('lib_document_actions')
            ->orderBy('title', 'ASC')
            ->orderBy('description', 'ASC')
            ->get();
    }

    //softdeleted excluded, DocumentActionController create, editDraft function
    //DocumentController create, editDraft function
    //RouteController create, editDraft function
    //DocumentActionController reply, detailsByDocIdSenderId function
    public function listOfDocumentActionsEnabledOnly(){  
        return DB::table('lib_document_actions')
            ->orderBy('title', 'ASC')
            ->orderBy('description', 'ASC')
            ->where('lib_document_actions.deleted_at', null)
            ->get();
    }

    public function searchDocumenActionById($id) //DocumentActionController, edit function
    {
        return DB::table('lib_document_actions')->where('id', $id)->get();
    }

    public function storeDocumentAction($request) //DocumentActionController, store function
    { 
        $res = new LibDocumentAction();
        $res->title = $request->title;
        $res->description = $request->description;
        $res->save();
    }

    public function updateDocumentAction($id, $request) //DocumentActionController, update function
    {
        $res = LibDocumentAction::where('id', '=', $id)->first(); //softdeleted will be not found
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

    public function disableDocumentAction($id) //DocumentActionController, disable function
    {
        LibDocumentAction::find($id)->delete();
    }

    public function enableDocumentAction($id) //DocumentActionController, enable function
    {
        LibDocumentAction::withTrashed()->where('id', $id)->restore();
    }
}
