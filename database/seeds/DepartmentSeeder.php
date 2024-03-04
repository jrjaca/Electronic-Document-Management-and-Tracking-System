<?php

use App\Model\Location\LibDepartment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lib_departments')->delete();
        
        $data = [
            [
                'title' => 'Information Technology Management Department',
                'remarks' => 'HEADOFFICE:',
            ],
            [
                'title' => 'Chief of Information Office',
                'remarks' => 'HEADOFFICE:',
            ],
            [
                'title' => 'Information Security Department',
                'remarks' => 'HEADOFFICE:',
            ],
            [
                'title' => 'NCR South',
                'remarks' => 'PRONCR:',
            ],
            [
                'title' => 'Office of the Vice-President',
                'remarks' => 'PRONCR:',
            ],
            [
                'title' => 'I.T. Unit',
                'remarks' => 'PRONCR:',
            ],
            [
                'title' => 'HR Unit',
                'remarks' => 'PRONCR:',
            ],
        ];

        LibDepartment::insert($data);
    }
}
