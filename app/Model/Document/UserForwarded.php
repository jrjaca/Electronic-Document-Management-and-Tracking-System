<?php

namespace App\Model\Document;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class UserForwarded extends Model
{
    protected $table = 'user_forwarded'; //to prevent base error in user_forwardeds
    
    public function getForwardedToUsersByDocId($docId){ //DocumentController editDraft function
        return UserForwarded::where('document_id', $docId)->get();
    }    

    public function getForwardedToUsersDetailsByDocId($docId){ //DocumentController reply, detailsByDocIdSenderId function
        return DB::table('user_forwarded as uf')
            ->leftJoin('users as r', 'uf.user_id', 'r.id') //recepient
            ->leftJoin('lib_offices as of', 'uf.office_id', 'of.id')
            ->leftJoin('lib_departments as de', 'uf.department_id', 'de.id')
            ->leftJoin('lib_sections as se', 'uf.section_id', 'se.id')
            ->select('uf.*',
                        'r.last_name as r_last_name', 'r.first_name as r_first_name', 'r.middle_name as r_middle_name', 'r.suffix_name as r_suffix_name',
                            'r.username as r_username', 'r.avatar as r_avatar',
                        'of.title as office_desc',
                        'de.title as department_desc',
                        'se.title as section_desc')
            ->where('uf.document_id', $docId) 
            ->orderBy('r.last_name', 'ASC')
            ->get();
    
    }
}
