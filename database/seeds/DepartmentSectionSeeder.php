<?php

use App\Model\Location\LibDepartmentSection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lib_department_sections')->delete();
        
        $data = [
            [
                'department_id' => '3',
                'section_id' => '3',
            ],
            [
                'department_id' => '3',
                'section_id' => '4',
            ],
            [
                'department_id' => '4',
                'section_id' => '12',
            ],
            [
                'department_id' => '4',
                'section_id' => '13',
            ],
            [
                'department_id' => '4',
                'section_id' => '8',
            ],
            [
                'department_id' => '4',
                'section_id' => '14',
            ],
            [
                'department_id' => '4',
                'section_id' => '11',
            ],
            [
                'department_id' => '4',
                'section_id' => '7',
            ],
            [
                'department_id' => '4',
                'section_id' => '5',
            ],
            [
                'department_id' => '4',
                'section_id' => '9',
            ],
            [
                'department_id' => '4',
                'section_id' => '6',
            ],
            [
                'department_id' => '4',
                'section_id' => '15',
            ],
            [
                'department_id' => '4',
                'section_id' => '10',
            ],
            [
                'department_id' => '1',
                'section_id' => '1',
            ],
            [
                'department_id' => '1',
                'section_id' => '2',
            ],
        ];

        LibDepartmentSection::insert($data);
    }
}
