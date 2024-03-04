<?php

namespace App\Model\Publish;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $primaryKey = 'announcement_id';
    public function getAnnouncementDetail(){ //PublishController createAnnouncement function , HomeController home function
        return Announcement::find(1);
    }
}
