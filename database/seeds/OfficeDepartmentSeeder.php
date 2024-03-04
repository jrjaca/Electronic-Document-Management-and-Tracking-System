<?php

use App\Model\Location\LibOfficeDepartment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfficeDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lib_office_departments')->delete();
        
        $data = [
            [
                'office_id' => '1',
                'department_id' => '2',
            ],
            [
                'office_id' => '1',
                'department_id' => '3',
            ],
            [
                'office_id' => '1',
                'department_id' => '1',
            ],
            [
                'office_id' => '2',
                'department_id' => '4',
            ],
            [
                'office_id' => '2',
                'department_id' => '5',
            ],
            [
                'office_id' => '2',
                'department_id' => '7',
            ],
            [
                'office_id' => '2',
                'department_id' => '6',
            ],
        ];

        LibOfficeDepartment::insert($data);
    }
}
