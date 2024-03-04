<?php

namespace App\Model\Document;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $primaryKey = 'document_id'; //must be assigned this since in DB auto increment 'id' is changed to document_id

    public function listOfCreatedDocumentsPerUserId($userId, $isDraft){ //softdeleted not included, DocumentController listDraft, listSubmitted function
        return DB::table('documents')
            ->leftJoin('lib_document_actions', 'documents.document_action_id', 'lib_document_actions.id')
            ->leftJoin('lib_document_types', 'documents.document_type_id', 'lib_document_types.id')
            ->select('documents.*', 'lib_document_actions.title as document_action_desc', 'lib_document_types.title as document_type_desc')
            ->where('documents.user_id', $userId)
            ->where('documents.is_draft', $isDraft)
            ->where('documents.deleted_at', null)
            ->orderBy('documents.created_at', 'DESC')
            ->get();
    }

    //DocumentController viewFullDocDetails, reply, detailsByDocIdSenderId function
    public function getDocumentFullDetailsByDocId($docId){ 
        return DB::table('documents')  //no ROUTES and SEEN_LOGS tables
            ->leftJoin('lib_document_actions', 'documents.document_action_id', 'lib_document_actions.id') //one
            ->leftJoin('lib_document_types', 'documents.document_type_id', 'lib_document_types.id') //one
            ->leftJoin('attached_documents', 'documents.document_id', 'attached_documents.document_id') //one
            //->leftJoin('users AS c', 'documents.courier_user_id', 'c.id') //courier
            ->leftJoin('users AS u', 'documents.user_id', 'u.id') //creator
               ->leftJoin('lib_offices as of', 'u.office_id', 'of.id') //creator
               ->leftJoin('lib_departments as de', 'u.department_id', 'de.id') //creator
               ->leftJoin('lib_sections as se', 'u.section_id', 'se.id') //creator
            //
            ->leftJoin('user_forwarded', 'documents.document_id', 'user_forwarded.document_id') //many  Link user_forwarded to documents as sub table, then to users for user name
            ->leftJoin('users AS f', 'user_forwarded.user_id', 'f.id') //many
            //
            ->select('documents.*', 'lib_document_actions.title as document_action_desc', 
                                    'lib_document_types.title as document_type_desc',
                                    'attached_documents.path', 'attached_documents.external_link', 'attached_documents.attachment_name',
                                    //'c.last_name as courier_last_name','c.first_name as courier_first_name','c.middle_name as courier_middle_name','c.suffix_name as courier_suffix_name',
                                    'u.last_name as u_last_name','u.first_name as u_first_name','u.middle_name as u_middle_name','u.suffix_name as u_suffix_name',
                                        'u.avatar', 'u.username',
                                    'of.title as office_desc',
                                    'de.title as department_desc',
                                    'se.title as section_desc',
                                    //
                                    'f.last_name as recipient_last_name','f.first_name as recipient_first_name','f.middle_name as recipient_middle_name','f.suffix_name as recipient_suffix_name')
            ->where('documents.document_id', $docId)
            ->where('documents.deleted_at', null)
            ->get();
    }

    //DocumentController editDraft function
    //PublishController createPublishedRoutedDoc function.
    public function getDocumentBasicDetailsByDocId($docId){ 
        return Document::where('document_id', $docId)->first();
    }

    //sensi
    //

}
