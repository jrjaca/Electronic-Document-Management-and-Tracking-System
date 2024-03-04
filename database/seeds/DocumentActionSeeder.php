<?php

use Carbon\Carbon;
use App\LibDocumentAction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lib_document_actions')->delete();
        
        $data = [
            [
                'title' => 'Replied',
                'created_at' => Carbon::now('Asia/Manila'),
                'updated_at' => Carbon::now('Asia/Manila')
            ],
            [
                'title' => 'For Approval',
                'created_at' => Carbon::now('Asia/Manila'),
                'updated_at' => Carbon::now('Asia/Manila')
            ],
            [
                'title' => 'Returned',
                'created_at' => Carbon::now('Asia/Manila'),
                'updated_at' => Carbon::now('Asia/Manila')
            ],
            [
                'title' => 'Approved',
                'created_at' => Carbon::now('Asia/Manila'),
                'updated_at' => Carbon::now('Asia/Manila')
            ],
            [
                'title' => 'Disapproved',
                'created_at' => Carbon::now('Asia/Manila'),
                'updated_at' => Carbon::now('Asia/Manila')
            ],
            [
                'title' => 'For Comment',
                'created_at' => Carbon::now('Asia/Manila'),
                'updated_at' => Carbon::now('Asia/Manila')
            ],
            [
                'title' => 'Acknowledged',
                'created_at' => Carbon::now('Asia/Manila'),
                'updated_at' => Carbon::now('Asia/Manila')
            ]
        ];

        LibDocumentAction::insert($data);
    }
}
