<?php

namespace App\Model\Document;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Route extends Model
{
    protected $primaryKey = 'route_id';

    public function getRouteDocBasicDetailsByRouteId($routeId){ //RouteController editDraft function
        return Route::find($routeId);
        //where('active', 1)->first();

        // return DB::table('routes as ro')
        //     ->leftJoin('lib_document_types as typ', 'ro.document_type_id', 'typ.id')
        //     ->leftJoin('lib_document_actions as act', 'ro.document_action_id', 'act.id')
        //     ->select('ro.*', 
        //                 'typ.title as type_desc',
        //                 'act.title as action_desc'
        //             )
        //     ->where('ro.deleted_at', null)
        //     ->where('ro.route_id', $routeId)
        //     ->orderBy('ro.route_id', 'DESC')
        //     ->first();
    }

    public function getRouteDocumentFullDetailsByRouteId($routeId){ //RouteController viewFullDocDetails function
        return DB::table('routes as ro')
            ->leftJoin('users as nu', 'ro.user_id', 'nu.id')
            ->leftJoin('users as tr', 'ro.terminal_user_id', 'tr.id')
            ->leftJoin('users as co', 'ro.courier_user_id', 'co.id')
            ->leftJoin('lib_document_types as typ', 'ro.document_type_id', 'typ.id')
            ->leftJoin('lib_document_actions as act', 'ro.document_action_id', 'act.id')
            ->leftJoin('lib_offices as off', 'ro.office_id', 'off.id')
            ->leftJoin('lib_departments as dep', 'ro.department_id', 'dep.id')
            ->leftJoin('lib_sections as sec', 'ro.section_id', 'sec.id')
            ->leftJoin('lib_offices as troff', 'ro.terminal_office_id', 'troff.id')
            ->leftJoin('lib_departments as trdep', 'ro.terminal_department_id', 'trdep.id')
            ->leftJoin('lib_sections as trsec', 'ro.terminal_section_id', 'trsec.id')
            ->select('ro.*', 
                        'nu.last_name as cre_last_name', 'nu.first_name as cre_first_name', 'nu.middle_name as cre_middle_name', 'nu.suffix_name as cre_suffix_name',
                        'tr.last_name as tr_last_name', 'tr.first_name as tr_first_name', 'tr.middle_name as tr_middle_name', 'tr.suffix_name as tr_suffix_name',
                        'co.last_name as co_last_name', 'co.first_name as co_first_name', 'co.middle_name as co_middle_name', 'co.suffix_name as co_suffix_name',
                        'typ.title as type_desc',
                        'act.title as action_desc',
                        'off.title as office_desc',
                        'dep.title as department_desc',
                        'sec.title as section_desc',
                        'troff.title as tr_office_desc',
                        'trdep.title as tr_department_desc',
                        'trsec.title as tr_section_desc'
                    )
            ->where('ro.deleted_at', null)
            ->where('ro.route_id', $routeId)
            ->first();
    }

    //draft, finalized, terminal = once; received, released = many
    //RouteController draft, finalized function
    public function getAllRouteDocBasicDetailsByUserIdStatusDFOnly($userId, $statusId){ //for Draft, Finalized and Terminal only
        return DB::table('routes as ro') 
            //->leftJoin('users as nu', 'ro.user_id', 'nu.id')
            //->leftJoin('users as co', 'ro.courier_user_id', 'co.id')
            ->leftJoin('lib_document_types as typ', 'ro.document_type_id', 'typ.id')
            ->leftJoin('lib_document_actions as act', 'ro.document_action_id', 'act.id')
            //->leftJoin('lib_offices as off', 'ro.office_id', 'off.id')
            //->leftJoin('lib_departments as dep', 'ro.department_id', 'dep.id')
            //->leftJoin('lib_sections as sec', 'ro.section_id', 'sec.id')
            ->select('ro.*', 
                        // 'nu.last_name as cre_last_name', 'nu.first_name as cre_first_name', 'nu.middle_name as cre_middle_name', 'nu.suffix_name as cre_suffix_name',
                        // 'co.last_name as co_last_name', 'co.first_name as co_first_name', 'co.middle_name as co_middle_name', 'co.suffix_name as co_suffix_name',
                        'typ.title as type_desc',
                        'act.title as action_desc'
                        // 'off.title as office_desc',
                        // 'dep.title as department_desc',
                        // 'sec.title as section_desc'
                    )
            ->where('ro.deleted_at', null)
            ->where('ro.user_id', $userId)
            ->where('ro.status', $statusId)
            ->orderBy('ro.route_id', 'DESC')
            ->get();
    }

    //RouteController terminal function
    public function getAllRouteDocBasicDetailsByUserIdStatusTerminalOnly($userId){ 
        // return DB::table('routes as ro') 
        //     //->leftJoin('users as nu', 'ro.user_id', 'nu.id')
        //     //->leftJoin('users as co', 'ro.courier_user_id', 'co.id')
        //     ->leftJoin('lib_document_types as typ', 'ro.document_type_id', 'typ.id')
        //     ->leftJoin('lib_document_actions as act', 'ro.document_action_id', 'act.id')
        //     //->leftJoin('lib_offices as off', 'ro.office_id', 'off.id')
        //     //->leftJoin('lib_departments as dep', 'ro.department_id', 'dep.id')
        //     //->leftJoin('lib_sections as sec', 'ro.section_id', 'sec.id')
        //     // ->select('ro.*', 
        //     ->select('ro.tracking_no', 'ro.title', 'ro.remarks', 'ro.route_id', 
        //                 // 'nu.last_name as cre_last_name', 'nu.first_name as cre_first_name', 'nu.middle_name as cre_middle_name', 'nu.suffix_name as cre_suffix_name',
        //                 // 'co.last_name as co_last_name', 'co.first_name as co_first_name', 'co.middle_name as co_middle_name', 'co.suffix_name as co_suffix_name',
        //                 'typ.title as type_desc',
        //                 'act.title as action_desc'
        //                 // 'off.title as office_desc',
        //                 // 'dep.title as department_desc',
        //                 // 'sec.title as section_desc'
        //             )
        //     ->where('ro.deleted_at', null)
        //     ->where('ro.user_id', $userId)
        //     // ->where('ro.terminal_user_id', $userId) 
        //     ->where('ro.status', 'F') //finalized(R and C are not included) and terminal_at is not null
        //     ->whereNotNull('ro.terminal_at')
        //     ->groupBy('ro.tracking_no', 'ro.title', 'ro.remarks', 'ro.route_id', 'type_desc', 'action_desc')
        //     ->orderBy('ro.route_id', 'DESC')
        //     ->get();


            $allDoc = DB::table('routes as ro') 
                ->leftJoin('lib_document_types as typ', 'ro.document_type_id', 'typ.id')
                ->leftJoin('lib_document_actions as act', 'ro.document_action_id', 'act.id')
                ->select('ro.tracking_no', 'ro.title', 'ro.remarks', 'ro.route_id', 
                            'typ.title as type_desc',
                            'act.title as action_desc'
                        )
                ->where('ro.deleted_at', null)
                ->where('ro.user_id', $userId)  //CREATED BY
                ->where('ro.status', 'F') //finalized(R and C are not included) and terminal_at is not null
                ->whereNotNull('ro.terminal_at')
                ->orderBy('ro.route_id', 'DESC');

            $allDocWithLoc = DB::table('routes as ro') 
                ->leftJoin('lib_document_types as typ', 'ro.document_type_id', 'typ.id')
                ->leftJoin('lib_document_actions as act', 'ro.document_action_id', 'act.id')
                ->select('ro.tracking_no', 'ro.title', 'ro.remarks', 'ro.route_id', 
                            'typ.title as type_desc',
                            'act.title as action_desc'
                        )
                ->where('ro.deleted_at', null)
                ->where('ro.terminal_user_id', $userId) //TERMINAL BY
                ->where('ro.status', 'F') //finalized(R and C are not included) and terminal_at is not null
                ->whereNotNull('ro.terminal_at')
                ->orderBy('ro.route_id', 'DESC');

            $allDoc = $allDoc->unionAll($allDocWithLoc);
                return $allDoc
                        ->orderBy('route_id', 'DESC')
                        ->get();
    }

    //draft, finalized, terminal = once; received, released = many
    //RouteController received, released function
    public function getAllRouteDocBasicDetailsByUserIdStatusRCOnly($userId, $statusId){ //for Received and Released only
        return DB::table('routes as ro') //tracking no, title, remarks, type
            //->leftJoin('users as nu', 'ro.user_id', 'nu.id')
            //->leftJoin('users as co', 'ro.courier_user_id', 'co.id')
            ->leftJoin('lib_document_types as typ', 'ro.document_type_id', 'typ.id')
            //->leftJoin('lib_document_actions as act', 'ro.document_action_id', 'act.id')
            //->leftJoin('lib_offices as off', 'ro.office_id', 'off.id')
            //->leftJoin('lib_departments as dep', 'ro.department_id', 'dep.id')
            //->leftJoin('lib_sections as sec', 'ro.section_id', 'sec.id')
            ->select('ro.route_id', 'ro.tracking_no', 'ro.title', 'ro.remarks',  //'ro.route_id' has been added
                        // 'nu.last_name as cre_last_name', 'nu.first_name as cre_first_name', 'nu.middle_name as cre_middle_name', 'nu.suffix_name as cre_suffix_name',
                        // 'co.last_name as co_last_name', 'co.first_name as co_first_name', 'co.middle_name as co_middle_name', 'co.suffix_name as co_suffix_name',
                        'typ.title as type_desc'//,
                        //'act.title as action_desc'
                        // 'off.title as office_desc',
                        // 'dep.title as department_desc',
                        // 'sec.title as section_desc'
                    )
            ->where('ro.deleted_at', null)
            ->where('ro.terminal_at', null)
            ->where('ro.user_id', $userId)
            ->where('ro.status', $statusId)
            ->groupBy('ro.route_id', 'ro.tracking_no', 'ro.title', 'ro.remarks', 'type_desc')  //'ro.route_id' has been added
            ->orderBy('ro.route_id', 'DESC')
            ->get();
    }

    //full details af document and route
    //RouteController routeTrail, searchByTrackingNo, searchByEncryptedId function
    public function getDocumentRoutesByTrackingNo($trackingNo){ 
        return DB::table('routes as ro')
            ->leftJoin('users as nu', 'ro.user_id', 'nu.id')
            ->leftJoin('users as tr', 'ro.terminal_user_id', 'tr.id')
            ->leftJoin('users as co', 'ro.courier_user_id', 'co.id')
            ->leftJoin('lib_document_types as typ', 'ro.document_type_id', 'typ.id')
            ->leftJoin('lib_document_actions as act', 'ro.document_action_id', 'act.id')
            ->leftJoin('lib_offices as off', 'ro.office_id', 'off.id')
            ->leftJoin('lib_departments as dep', 'ro.department_id', 'dep.id')
            ->leftJoin('lib_sections as sec', 'ro.section_id', 'sec.id')
            ->leftJoin('lib_offices as troff', 'ro.terminal_office_id', 'troff.id')
            ->leftJoin('lib_departments as trdep', 'ro.terminal_department_id', 'trdep.id')
            ->leftJoin('lib_sections as trsec', 'ro.terminal_section_id', 'trsec.id')
            ->select('ro.*', 
                        'nu.last_name as cre_last_name', 'nu.first_name as cre_first_name', 'nu.middle_name as cre_middle_name', 'nu.suffix_name as cre_suffix_name',
                        'tr.last_name as tr_last_name', 'tr.first_name as tr_first_name', 'tr.middle_name as tr_middle_name', 'tr.suffix_name as tr_suffix_name',
                        'co.last_name as co_last_name', 'co.first_name as co_first_name', 'co.middle_name as co_middle_name', 'co.suffix_name as co_suffix_name',
                        'typ.title as type_desc',
                        'act.title as action_desc',
                        'off.title as office_desc',
                        'dep.title as department_desc',
                        'sec.title as section_desc',
                        'troff.title as tr_office_desc',
                        'trdep.title as tr_department_desc',
                        'trsec.title as tr_section_desc'
                    )
            ->where('ro.deleted_at', null)
            ->where('ro.tracking_no', $trackingNo)
            // ->orderBy('ro.routed_at', 'DESC')
            ->orderBy('ro.route_id', 'DESC')   //to get the latest/top
            ->get();
    }

    //RouteContoller tagAsTerminalPost, submitReceiving function
    public function getLastRouteBasicInfoByTrackingNo($trackingNo){
        return DB::table('routes as ro')
            //->where('ro.status', 'C')
            ->where('ro.tracking_no', $trackingNo)
            ->orderBy('ro.route_id', 'DESC')
            ->first();
    }

    //RouteContoller submitReceiving, submitReleasing, tagAsTerminal function
    public function UpdateReceivedAtByTrackingNo($trackingNo){
        //update
        $data = Route::where('tracking_no', '=', $trackingNo)  
                        ->where('received_at', null)
                        ->orderBy('route_id', 'DESC')
                        ->first(); 
        $data->received_at = Carbon::now('Asia/Manila');
        $data->save();
    }

    //RouteContoller submitReceiving function
    public function GetRecepientsEmailRouteByTrackingNo($trackingNo){
        return DB::table('routes as ro') 
            ->leftJoin('users as usr', 'ro.user_id', 'usr.id')
            ->select('usr.last_name as usr_last_name', 'usr.first_name as usr_first_name', 
                        'usr.middle_name as usr_middle_name', 'usr.suffix_name as usr_suffix_name', 'usr.email as usr_email')
            ->where('ro.deleted_at', null)
            ->where('ro.tracking_no', $trackingNo)
            ->groupBy('usr_last_name', 'usr_first_name', 'usr_middle_name', 'usr_suffix_name', 'usr_email')
            ->orderBy('ro.route_id', 'DESC')
            ->get();
    }

    // public function getAllDraftRouteDocumentBasicDetailsByUserId($userId){ //RouteController  function
    //     return DB::table('routes as ro')
    //         ->leftJoin('lib_document_types as typ', 'ro.document_type_id', 'typ.id')
    //         ->leftJoin('lib_document_actions as act', 'ro.document_action_id', 'act.id')
    //         ->select('ro.*', 
    //                     'typ.title as type_desc',
    //                     'act.title as action_desc'
    //                 )
    //         ->where('ro.deleted_at', null)
    //         ->where('ro.status', 'D') //draft
    //         ->where('ro.user_id', $userId)
    //         ->orderBy('ro.route_id', 'DESC')
    //         ->get();
    // }

//////////////////////////////////////////////////////////////////
    public function getAllReleasedRouteDocumentFullDetails(){ 
        return DB::table('routes as ro')
            ->leftJoin('users as nu', 'ro.user_id', 'nu.id')
            ->leftJoin('users as co', 'ro.courier_user_id', 'co.id')
            ->leftJoin('lib_document_types as typ', 'ro.document_type_id', 'typ.id')
            ->leftJoin('lib_document_actions as act', 'ro.document_action_id', 'act.id')
            ->leftJoin('lib_offices as off', 'ro.office_id', 'off.id')
            ->leftJoin('lib_departments as dep', 'ro.department_id', 'dep.id')
            ->leftJoin('lib_sections as sec', 'ro.section_id', 'sec.id')
            ->select('ro.*', 
                        'nu.last_name as cre_last_name', 'nu.first_name as cre_first_name', 'nu.middle_name as cre_middle_name', 'nu.suffix_name as cre_suffix_name',
                        'co.last_name as co_last_name', 'co.first_name as co_first_name', 'co.middle_name as co_middle_name', 'co.suffix_name as co_suffix_name',
                        'typ.title as type_desc',
                        'act.title as action_desc',
                        'off.title as office_desc',
                        'dep.title as department_desc',
                        'sec.title as section_desc'
                    )
            ->where('ro.deleted_at', null)
            ->orderBy('ro.route_id', 'DESC')
            ->get();
    }

    public function getDocumentRouteDetailsByDocId($docId){ //DocumentController viewDocRouteDetails function
        return DB::table('routes')
            ->leftJoin('users', 'routes.user_id', 'users.id')
            ->leftJoin('lib_document_actions as act', 'routes.document_action_id', 'act.id')
            ->leftJoin('lib_offices as off', 'routes.office_id', 'off.id')
            ->leftJoin('lib_departments as dep', 'routes.department_id', 'dep.id')
            ->leftJoin('lib_sections as sec', 'routes.section_id', 'sec.id')
            ->leftJoin('documents as doc', 'routes.document_id', 'doc.document_id')
            ->select('routes.*', 
                        'users.last_name', 'users.first_name', 'users.middle_name', 'users.suffix_name',
                        'act.title as action_desc',
                        'off.title as office_desc',
                        'dep.title as department_desc',
                        'sec.title as section_desc',
                        'doc.title as doc_title', 'doc.barcode', 'doc.remarks')
            ->where('routes.document_id', $docId)
            // ->whereIn('routes.status', ['1','2']) //released, received hardcopy document only
            ->orderBy('routes.route_id', 'DESC')
            ->get();
    }


/////////////////////////////  FOR DELETE ALL BELOW --------------------------------------------------------------
    
    //02-20-2022
    //DocumentController getFullDocDetailsForRoutingOnlyByBarcode function
    // public function getDocumentFullDetailsForRoutingOnlyByBarcode($barcode){ 
    //     return DB::table('routes as r') //many
    //         ->leftJoin('documents as d',  'r.document_id', 'd.document_id') 
    //         ->leftJoin('lib_document_actions as da', 'd.document_action_id', 'da.id') //one
    //         ->leftJoin('lib_document_types as dt', 'd.document_type_id', 'dt.id') //one
    //         ->leftJoin('attached_documents as ad', 'd.document_id', 'ad.document_id') //one
    //         //->leftJoin('users AS c', 'd.courier_user_id', 'c.id') //courier           
    //         ->leftJoin('users AS u', 'r.user_id', 'u.id') //router
    //         ->leftJoin('lib_offices as uof', 'r.office_id', 'uof.id') //router
    //         ->leftJoin('lib_departments as ude', 'r.department_id', 'ude.id') //router
    //         ->leftJoin('lib_sections as use', 'r.section_id', 'use.id') //router
    //         //->leftJoin('user_forwarded', 'd.document_id', 'user_forwarded.document_id') //many  Link user_forwarded to documents as sub table, then to users for user name
    //         //->leftJoin('users AS f', 'user_forwarded.user_id', 'f.id') //many
    //         ->select('r.*', 'da.title as document_action_desc', 
    //                                 'dt.title as document_type_desc',
    //                                 'ad.path', 'ad.external_link', 'ad.attachment_name',
    //                                 //'c.last_name as courier_last_name','c.first_name as courier_first_name','c.middle_name as courier_middle_name','c.suffix_name as courier_suffix_name',
    //                                 'r.status as r_status', 'r.created_at as r_created_at',
    //                                 'u.last_name as u_last_name','u.first_name as u_first_name','u.middle_name as u_middle_name','u.suffix_name as u_suffix_name',
    //                                     //'u.avatar', 'u.username',
    //                                 'uof.title as office_desc',
    //                                 'ude.title as department_desc',
    //                                 'use.title as section_desc')
    //                                 //
    //                                 //'f.last_name as recipient_last_name','f.first_name as recipient_first_name','f.middle_name as recipient_middle_name','f.suffix_name as recipient_suffix_name')
    //         ->where('d.barcode', $barcode)
    //         //->where('d.is_hardcopy', true) //routing with hard copy
    //         ->whereIn('r.status', ['1','2']) //released, received hardcopy document only
    //         ->where('d.deleted_at', null) //not disabled/deleted
    //         ->get();
    //         //->orderBy('r.created_at', 'DESC') //last status only
    //         //->first();
    // }

    //DocumentController saveNewDocument, submitOrUpdateDraftDocument, replying function
    public function insertRouteGetId($user_id, $document_id, $title, $document_type_id, $document_action_id, $status, $office_id, $department_id, $section_id, $created_at){ 
        $route_arr = array(
            //'document_id' => $document_id,
            'user_id' => $user_id, //s
            'document_id' => $document_id,
            'title' => $title,
            'document_type_id' => $document_type_id,
            'document_action_id' => $document_action_id,
            'status' => $status,  //Released
            'office_id' => $office_id,
            'department_id' => $department_id,
            'section_id' => $section_id,
            'created_at' => $created_at
        );        
        return Route::insertGetId($route_arr);
    }
}
