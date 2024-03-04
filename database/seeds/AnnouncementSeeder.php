<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Model\Publish\Announcement;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('announcements')->delete();
        
        $data = [
            [
                'user_id' => 20556408,
                'path' => NULL,
                'title' => 'PRACTICE',
                'sub_title' => 'Fire Drill',
                'details' => 'All employees are task to join the fire drill on February 1, 2021 and will commence at 2:00 PM.
                                Attendance is a must!',
                'updated_byuser_id' => 2,
                'created_at' => Carbon::now('Asia/Manila'),
                'updated_at' => Carbon::now('Asia/Manila')
            ],
        ];

        Announcement::insert($data);
    }
}
