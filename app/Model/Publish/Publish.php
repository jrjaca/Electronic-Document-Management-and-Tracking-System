<?php

namespace App\Model\Publish;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Publish extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'published_id'; 
    //
    public function GetPublishedDocsByPublishedId($publishedId){ //PublishController editPublishedDoc function
        return Publish::find($publishedId);
    }

    public function GetPublishedDocsByDocumentId($documentId){ //PublishController createPublishedRoutedDoc function
        return Publish::where('document_id', $documentId)->exists();
    }

    public function GetPublishedDocsByUserId($userId){ //PublishController listPublished function
        return DB::table('publishes as p')
        ->leftJoin('lib_document_types as t', 'p.document_type_id', 't.id')
        ->select('p.*', 
                't.title as document_type_desc',
                )
        ->where('p.deleted_at', null)
        ->where('p.user_id', $userId)
        ->orderBy('p.published_at', 'DESC')
        ->get();
    }

    public function GetTempDeletedPublishedDocsByUserId($userId){ //PublishController listTemporaryDeleted function
        return DB::table('publishes as p')
        ->leftJoin('lib_document_types as t', 'p.document_type_id', 't.id')
        ->select('p.*', 
                't.title as document_type_desc',
                )
        ->whereNotNull('p.deleted_at')
        ->where('p.user_id', $userId)
        ->orderBy('p.published_at', 'DESC')
        ->get();
    }

    //index.blade.php (main), list.blade.php, deleted-list.blade (publish)
    public function GetPublishedDocsFullDetailsByPublishedId($publishedId){ //HomeController viewPublishedDocFullDetails function
        return DB::table('publishes as p')
        ->leftJoin('users as uc', 'p.user_id', 'uc.id')
        ->leftJoin('users as ue', 'p.updated_byuser_id', 'ue.id')
        ->leftJoin('users as ud', 'p.deleted_byuser_id', 'ud.id')
        ->leftJoin('lib_document_types as t', 'p.document_type_id', 't.id')
        ->leftJoin('lib_offices as o', 'p.office_id', 'o.id')
        ->leftJoin('lib_departments as d', 'p.department_id', 'd.id')
        ->select('p.*', 
                'uc.last_name as uc_last_name', 'uc.first_name as uc_first_name', 'uc.middle_name as uc_middle_name', 'uc.suffix_name as uc_suffix_name',
                'ue.last_name as ue_last_name', 'ue.first_name as ue_first_name', 'ue.middle_name as ue_middle_name', 'ue.suffix_name as ue_suffix_name',
                'ud.last_name as ud_last_name', 'ud.first_name as ud_first_name', 'ud.middle_name as ud_middle_name', 'ud.suffix_name as ud_suffix_name',
                't.title as document_type_desc',
                'o.id as office_id','o.title as office_desc',
                'd.id as department_id','d.title as department_desc')
        //->where('p.deleted_at', null)
        ->where('p.published_id', $publishedId)
        //->orderBy('p.published_at', 'DESC')
        ->get();
    }

    //HomeController home, listDocByType function
    public function GetPublishedDocsByDocTypeLocCount($isLoggedIn, $docTypeId, $officeId, $deptId, $count){ 

        if (!$isLoggedIn){ //if not logged in
            //all published docs without specific location, therefore for public
            return DB::table('publishes as p')
            ->leftJoin('lib_document_types as t', 'p.document_type_id', 't.id')
            ->select('p.*', 
                    't.title as document_type_desc')
            ->where('p.deleted_at', null)
            ->where('p.office_id', null)
            ->where('p.department_id', null)
            ->where('p.document_type_id', $docTypeId)
            ->orderBy('p.published_at', 'DESC')
            //->offset(0)
            ->limit($count)
            ->get();
        } else {
            //all published docs without specific location
            $allDoc = DB::table('publishes as p')
            ->leftJoin('lib_document_types as t', 'p.document_type_id', 't.id')
            ->select('p.*', 
                    't.title as document_type_desc')
            ->where('p.deleted_at', null)
            ->where('p.office_id', null)
            ->where('p.department_id', null)
            ->where('p.document_type_id', $docTypeId)
            ->orderBy('p.published_at', 'DESC')
            ->limit($count);

            //With office only
            $allDocWithLoc = DB::table('publishes as p')
            ->leftJoin('lib_document_types as t', 'p.document_type_id', 't.id')
            ->select('p.*', 
                    't.title as document_type_desc')
            ->where('p.deleted_at', null)
            ->where('p.office_id', $officeId)
            ->where('p.department_id', null)
            ->where('p.document_type_id', $docTypeId)
            ->orderBy('p.published_at', 'DESC')
            ->limit($count);
            $allDoc = $allDoc->unionAll($allDocWithLoc);

            //With office and department
            $allDocWithLoc = DB::table('publishes as p')
            ->leftJoin('lib_document_types as t', 'p.document_type_id', 't.id')
            ->select('p.*', 
                    't.title as document_type_desc')
            ->where('p.deleted_at', null)
            ->where('p.office_id', $officeId)
            ->where('p.department_id', $deptId)
            ->where('p.document_type_id', $docTypeId)
            ->orderBy('p.published_at', 'DESC')
            ->limit($count);
            $allDoc = $allDoc->unionAll($allDocWithLoc);

            return $allDoc
                    ->orderBy('published_at', 'DESC')
                    ->limit($count)
                    ->get();
                    //->groupBy('first_name', 'status')
        }  
    }

    public function GetPublishedDocByAdvCriteria($isLoggedIn, $officeId, $deptId, $docTypeId, $docName, $fileName, $pubStartDate, $pubEndDate, $remarks){

        // $start_date = Carbon::parse("$req->startDate 00:00:00")->format('Y-m-d H:i:s');
        // $end_date = Carbon::parse("$req->endDate 23:59:59")->format('Y-m-d H:i:s');
        // $events = Events::where([['start_date','<=',$start_date],['end_date','>=',$end_date]])
        //     ->orwhereBetween('start_date',array($start_date,$end_date))
        //     ->orWhereBetween('end_date',array($start_date,$end_date))->get();

        // $subject = Request::input('subject');
        // $query = DB::table('course_listings');
        // if ($subject) {
        //     $query->where('subject', $subject);
        // }
        // $results = $query->get();


        if (!$isLoggedIn){ //if not logged in
            //all published docs without specific location, therefore for public
            $query = DB::table('publishes as p');

            //crieteria
            if ($docTypeId != '')    { $query->where('p.document_type_id', $docTypeId); }
            if ($docName != '')      { $query->where('p.title', 'like', '%'.$docName.'%'); }
            if ($fileName != '')     { $query->where('p.attachment_name', 'like', '%'.$fileName.'%'); }
            if ($pubStartDate != '') { $query->whereBetween('published_at', [$pubStartDate.' 00:00:00', $pubEndDate.' 23:59:59']); }    
            if ($remarks != '')      { $query->where('p.remarks', 'like', '%'.$remarks.'%'); }

            $query->leftJoin('lib_document_types as t', 'p.document_type_id', 't.id');
            $query->select('p.*', 't.title as document_type_desc');
            $query->where('p.deleted_at', null);
            $query->where('p.office_id', null);
            $query->where('p.department_id', null);
            //->where('p.document_type_id', $docTypeId)
            $query->orderBy('p.published_at', 'DESC');
            return $query->get();

        } else {
            //-----------------all published docs without specific location---------
            $allDoc = DB::table('publishes as p');
            //crieteria
            if ($docTypeId != '')    { $allDoc->where('p.document_type_id', $docTypeId); }           
            if ($docName != '')      { $allDoc->where('p.title', 'like', '%'.$docName.'%');  }
            if ($fileName != '')     { $allDoc->where('p.attachment_name', 'like', '%'.$fileName.'%'); }
            if ($pubStartDate != '') { $allDoc->whereBetween('published_at', [$pubStartDate.' 00:00:00', $pubEndDate.' 23:59:59']); }    
            if ($remarks != '')      { $allDoc->where('p.remarks', 'like', '%'.$remarks.'%'); }

            $allDoc->leftJoin('lib_document_types as t', 'p.document_type_id', 't.id');
            $allDoc->select('p.*', 't.title as document_type_desc');
            $allDoc->where('p.deleted_at', null);
            $allDoc->where('p.office_id', null);
            $allDoc->where('p.department_id', null);
            $allDoc->orderBy('p.published_at', 'DESC');

            //-------------------With office only----------------
            $allDocWithLoc = DB::table('publishes as p');
            //crieteria
            if ($docTypeId != '')    { $allDocWithLoc->where('p.document_type_id', $docTypeId); }
            if ($officeId != '')     { $allDocWithLoc->where('p.office_id', $officeId); }
            //if ($deptId != '')       { $allDocWithLoc->where('p.department_id', 'like', $deptId); }            
            if ($docName != '')      { $allDocWithLoc->where('p.title', 'like', '%'.$docName.'%');  }
            if ($fileName != '')     { $allDocWithLoc->where('p.attachment_name', 'like', '%'.$fileName.'%'); }
            if ($pubStartDate != '') { $allDocWithLoc->whereBetween('published_at', [$pubStartDate.' 00:00:00', $pubEndDate.' 23:59:59']); }    
            if ($remarks != '')      { $allDocWithLoc->where('p.remarks', 'like', '%'.$remarks.'%'); }

            $allDocWithLoc->leftJoin('lib_document_types as t', 'p.document_type_id', 't.id');
            $allDocWithLoc->select('p.*', 't.title as document_type_desc');
            $allDocWithLoc->where('p.deleted_at', null);
            //$allDocWithLoc->where('p.office_id', $officeId);
            $allDocWithLoc->where('p.department_id', null);
            //$allDocWithLoc->where('p.document_type_id', $docTypeId);
            $allDocWithLoc->orderBy('p.published_at', 'DESC');
            $allDoc = $allDoc->unionAll($allDocWithLoc);

            //------------------With office and department---------------------
            $allDocWithLoc = DB::table('publishes as p');
            //crieteria
            if ($docTypeId != '')    { $allDocWithLoc->where('p.document_type_id', $docTypeId); }
            if ($officeId != '')     { $allDocWithLoc->where('p.office_id', $officeId); }
            if ($deptId != '')       { $allDocWithLoc->where('p.department_id', 'like', $deptId); }            
            if ($docName != '')      { $allDocWithLoc->where('p.title', 'like', '%'.$docName.'%');  }
            if ($fileName != '')     { $allDocWithLoc->where('p.attachment_name', 'like', '%'.$fileName.'%'); }
            if ($pubStartDate != '') { $allDocWithLoc->whereBetween('published_at', [$pubStartDate.' 00:00:00', $pubEndDate.' 23:59:59']); }    
            if ($remarks != '')      { $allDocWithLoc->where('p.remarks', 'like', '%'.$remarks.'%'); }

            $allDocWithLoc->leftJoin('lib_document_types as t', 'p.document_type_id', 't.id');
            $allDocWithLoc->select('p.*', 't.title as document_type_desc');
            $allDocWithLoc->where('p.deleted_at', null);
            $allDocWithLoc->orderBy('p.published_at', 'DESC');

            $allDoc = $allDoc->unionAll($allDocWithLoc);

            return $allDoc
                    ->orderBy('published_at', 'DESC')
                    ->get();
                    //->groupBy('first_name', 'status')
        } 
    }

    public function taggedAsUnpublished($published_id){ //PublishController temporarilyDeletePublishedDoc function   
        $data = Publish::where('published_id', $published_id)->first();
        $data->deleted_byuser_id = Auth::user()->id;
        $data->is_published = false;
        $data->save();
    }

    public function taggedAsPublished($published_id){ //PublishController rePublishDoc function   
        $data = Publish::where('published_id', $published_id)->first();
        $data->updated_byuser_id = Auth::user()->id;
        $data->is_published = true;
        $data->save();
    }
}
