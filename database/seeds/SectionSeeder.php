<?php

use App\Model\Location\LibSection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lib_sections')->delete();
        
        $data = [
            [
                'title' => 'ITRMD',
                'remarks' => 'ITMD:',
            ],
            [
                'title' => 'Technical Support',
                'remarks' => 'ITMD:',
            ],
            [
                'title' => 'IT Helpdesk',
                'remarks' => 'INFOSEC:',
            ],
            [
                'title' => 'Office of the Manager (Infosec)',
                'remarks' => 'INFOSEC:',
            ],
            [
                'title' => 'LHIO Makati',
                'remarks' => 'NCRSOUTH:',
            ],
            [
                'title' => 'LHIO Pasig',
                'remarks' => 'NCRSOUTH:',
            ],
            [
                'title' => 'LHIO Las Piñas',
                'remarks' => 'NCRSOUTH:',
            ],
            [
                'title' => 'Business Center - SM Aura',
                'remarks' => 'NCRSOUTH:',
            ],
            [
                'title' => 'LHIO Parañaque',
                'remarks' => 'NCRSOUTH:',
            ],
            [
                'title' => 'Office of the Manager (NCR South)',
                'remarks' => 'NCRSOUTH:',
            ],
            [
                'title' => 'I.T. Unit',
                'remarks' => 'NCRSOUTH:',
            ],
            [
                'title' => 'Admin Unit',
                'remarks' => 'NCRSOUTH:',
            ],
            [
                'title' => 'Benefits and Availment Section',
                'remarks' => 'NCRSOUTH:',
            ],
            [
                'title' => 'Collection Section',
                'remarks' => 'NCRSOUTH:',
            ],
            [
                'title' => 'Membership Section',
                'remarks' => 'NCRSOUTH:',
            ],
        ];

        LibSection::insert($data);
    }
}
