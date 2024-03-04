<?php

namespace App\Model\Document;

use Illuminate\Database\Eloquent\Model;

class AttachedDocument extends Model
{
    protected $primaryKey = 'attached_document_id';

    //DocumentController editDraft function
    //PublishController createPublishedRoutedDoc function
    public function getFileInfoEnabledOnlyByDocId($docId){ 
        return AttachedDocument::where('document_id', $docId)->first();
    }
}
