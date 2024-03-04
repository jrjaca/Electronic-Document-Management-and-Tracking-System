<?php

use Illuminate\Database\Seeder;
use App\Model\Location\LibOffice;
use Illuminate\Support\Facades\DB;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lib_offices')->delete();
        
        $data = [
            [
                'title' => 'Head Office',
                'remarks' => '1:',
            ],
            [
                'title' => 'PRO NCR',
                'remarks' => '2:',
            ],
        ];

        LibOffice::insert($data);
    }
}
