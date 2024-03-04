<?php

namespace App\Model\Document;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class SeenLog extends Model
{
    protected $primaryKey = 'seen_log_id';
    public $timestamps = false;

    public function getUserSeenLogsDetailsByDocId($docId){ //DocumentController viewSeenLogsDetails function
        return DB::table('seen_logs')
            ->join('documents as doc', 'seen_logs.document_id', 'doc.document_id')
            ->leftJoin('users as s', 'seen_logs.sender_id', 's.id')
            ->leftJoin('users as r', 'seen_logs.recipient_id', 'r.id')
            ->leftJoin('lib_document_actions as act', 'seen_logs.document_action_id', 'act.id')
            ->select('seen_logs.*', 
                        's.last_name as s_last_name', 's.first_name as s_first_name', 's.middle_name as s_middle_name', 's.suffix_name as s_suffix_name',
                        'r.last_name as r_last_name', 'r.first_name as r_first_name', 'r.middle_name as r_middle_name', 'r.suffix_name as r_suffix_name',
                        'act.title as action_desc',
                        'doc.title as doc_title', 'doc.barcode', 'doc.remarks', 'doc.updated_at as d_updated_at', 'doc.created_at as d_created_at', 'doc.submitted_at as d_submitted_at')
            ->where('seen_logs.document_id', $docId)
            ->orderBy('seen_logs.seen_log_id', 'DESC')
            ->get();
    }

    public function getReceivedDocsByUserId($userId){ //DocumentController listReceived function
        return DB::table('seen_logs as sl')
            ->join('documents as doc', 'sl.document_id', 'doc.document_id')
            //->join('user_forwarded as uf', function ($join) { //join, must be exist in user_forwarded table
            //    $join->on('sl.recipient_id', '=', 'uf.user_id') //multiple criteria on join. userid and doc id must be both exist in seen_logs and user_forwarded table.
            //        ->on('uf.document_id', '=', 'sl.document_id'); 
            //})

            ->leftJoin('users as s', 'sl.sender_id', 's.id')
            //->leftJoin('users as r', 'sl.recipient_id', 'r.id')
            ->leftJoin('lib_document_actions as act', 'doc.document_action_id', 'act.id') //get from document tbl
            ->leftJoin('lib_document_types as typ', 'doc.document_type_id', 'typ.id')
            ->select('sl.*', 'sl.submitted_at as sl_submitted_at',
                        's.last_name as s_last_name', 's.first_name as s_first_name', 's.middle_name as s_middle_name', 's.suffix_name as s_suffix_name',
                        //'r.last_name as r_last_name', 'r.first_name as r_first_name', 'r.middle_name as r_middle_name', 'r.suffix_name as r_suffix_name',
                        'act.title as doc_action_desc',
                        'typ.title as doc_type_desc',
                        'doc.title as doc_title', 'doc.barcode', 'doc.remarks', 'doc.updated_at as d_updated_at', 'doc.created_at as d_created_at', 'doc.created_at as d_submitted_at')
            //->where('sl.document_id', $docId)
            ->where('sl.recipient_id', $userId)
            ->where('sl.replied_at', null) //not yet responded
            ->orderBy('sl.seen_log_id', 'DESC')
            ->get();
    }

    public function getReleasedDocsByUserId($userId){ //DocumentController listReleased function
        // return DB::table('seen_logs as sl')
        //     ->join('documents as doc', 'sl.document_id', 'doc.document_id')
        //     //->leftJoin('users as s', 'sl.sender_id', 's.id')
        //     ->leftJoin('users as r', 'sl.recipient_id', 'r.id')
        //     ->leftJoin('lib_document_actions as act', 'doc.document_action_id', 'act.id') //get from document tbl
        //     ->leftJoin('lib_document_types as typ', 'doc.document_type_id', 'typ.id')
        //     ->select('sl.*', 'sl.submitted_at as sl_submitted_at',
        //                 //'s.last_name as s_last_name', 's.first_name as s_first_name', 's.middle_name as s_middle_name', 's.suffix_name as s_suffix_name',
        //                 'r.last_name as r_last_name', 'r.first_name as r_first_name', 'r.middle_name as r_middle_name', 'r.suffix_name as r_suffix_name',
        //                 'act.title as doc_action_desc',
        //                 'typ.title as doc_type_desc',
        //                 'doc.title as doc_title', 'doc.barcode', 'doc.remarks', 'doc.updated_at as d_updated_at', 'doc.created_at as d_created_at', 'doc.created_at as d_submitted_at')
        //     //->where('sl.document_id', $docId)
        //     ->where('sl.sender_id', $userId) 
        //     //->whereNotNull('sl.replied_at') //already responded
        //     ->orderBy('sl.seen_log_id', 'DESC')
        //     ->get();

            return DB::table('seen_logs as sl')
            ->join('documents as doc', 'sl.document_id', 'doc.document_id')
            ->leftJoin('users as r', 'sl.recipient_id', 'r.id')
            ->leftJoin('lib_document_actions as act', 'doc.document_action_id', 'act.id') //get from document tbl
            ->leftJoin('lib_document_types as typ', 'doc.document_type_id', 'typ.id')
            ->select('sl.document_id',//'sl.submitted_at as sl_submitted_at', //'sl.recipient_id as sl_recipient_id', 
                        // 'r.last_name as r_last_name', 'r.first_name as r_first_name', 'r.middle_name as r_middle_name', 'r.suffix_name as r_suffix_name',
                        'act.title as doc_action_desc',
                        'typ.title as doc_type_desc',
                        'doc.title as doc_title', 'doc.barcode')
            ->where('sl.sender_id', $userId) 
            ->groupBy('sl.document_id', //'sl_submitted_at', //'sl_recipient_id', 
                        // 'r_last_name', 'r_first_name', 'r_middle_name', 'r_suffix_name',
                        'doc_action_desc',
                        'doc_type_desc',
                        'doc_title', 'doc.barcode')
            ->orderBy('sl.seen_log_id', 'DESC')
            //->max('sl.submitted_at')
            ->get();
    }

    public function getReceivedDocDetailsByDocId($docId){ // DocumentController reply, detailsByDocIdSenderId function, $recipientId
        return DB::table('seen_logs as sl')
            ->leftJoin('users as s', 'sl.sender_id', 's.id') //sender
            ->leftJoin('attached_documents as att', 'sl.attached_document_id', 'att.attached_document_id')
            ->leftJoin('lib_document_actions as act', 'sl.document_action_id', 'act.id')
            ->select('sl.route_id', 'sl.document_id', 'sl.attached_document_id', 'sl.sender_id', 'sl.document_action_id', 'sl.reply', 'sl.submitted_at',
                    's.last_name as s_last_name', 's.first_name as s_first_name', 's.middle_name as s_middle_name', 's.suffix_name as s_suffix_name', 's.avatar as s_avatar', 's.username as s_username',
                    'att.attachment_name as att_attachment_name', 'att.path as att_path', 'att.external_link as att_external_link', 'att.created_at as att_created_at', 'att.updated_at as att_updated_at',
                    'act.title as act_title')
            ->where('sl.document_id', $docId) 
            ->groupBy('sl.route_id', 'sl.document_id', 'sl.attached_document_id', 'sl.sender_id', 'sl.document_action_id', 'sl.reply', 'sl.submitted_at',
                        's_last_name', 's_first_name', 's_middle_name', 's_suffix_name', 's_avatar', 's_username',
                        'att_attachment_name', 'att_path', 'att_external_link', 'att_created_at', 'att_updated_at',
                        'act_title')
            ->orderBy('sl.route_id', 'ASC')
            ->get();
    }

    public function tagDocumentAsSeen($docId, $recipientId){
        DB::table('seen_logs')
            ->where('document_id',$docId)
            ->where('recipient_id',$recipientId)
            ->update(['last_seen_at' => Carbon::now('Asia/Manila')]);
    }

    public function insertSeenLog($route_id, $document_id, $attached_document_id, $document_action_id, $sender_id, $recipient_id, $reply, $submitted_at){ //DocumentController saveNewDocument, submitOrUpdateDraftDocument function
        $seenlog_arr = array(
            'route_id' => $route_id,
            'document_id' => $document_id,
            'attached_document_id' => $attached_document_id,
            'document_action_id' => $document_action_id,
            'sender_id' => $sender_id,
            'recipient_id' => $recipient_id,
            'reply' => $reply, 
            'submitted_at' => $submitted_at
        );        
        return SeenLog::insert($seenlog_arr);
    }

    public function taggedAsReplied($document_id, $recipient_id){ //DocumentController replying function   
        //search last message
        $seenLogData = SeenLog::where('document_id', $document_id)
                        ->where('recipient_id', $recipient_id)
                        //->orWhere('sender_id', $recipient_id)
                        ->where('replied_at', null)
                        ->first();
        $seenLogData->replied_at = Carbon::now('Asia/Manila');
        $seenLogData->save();
    }
            
}
