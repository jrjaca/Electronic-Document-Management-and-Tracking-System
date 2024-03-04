<?php

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('users')->insert([
        //     [
        //         'name' => 'Virgilio M. Jaca Jr.',
        //         'email' => 'virgilio_jacajr@yahoo.com',
        //         'password' => Hash::make('11111111'),
        //     ]
        // ]);

        DB::table('users')->delete();
        $data = [
            //------ HEAD OFFICE USER --------//
                                            [//1
                                                //'name' => '(MAINTAINER/SUPERUSER/USER MANAGER) Virgilio M. Jaca Jr.',
                                                'username' => '20556408',
                                                'last_name' => 'Jaca',
                                                'first_name' => 'Virgilio',
                                                'middle_name' => 'Mendoza',
                                                'suffix_name' => 'Jr.',
                                                'email' => 'virgilio_jacajr@yahoo.com',
                                                'office_id' => '1',
                                                'department_id' => '2',
                                                'section_id' => null,
                                                'password' => Hash::make('11111111'),
                                                'activated' => '1',
                                                'created_at' => Carbon::now('Asia/Manila'),
                                            ],

            //------ PRO NCR USER --------//
                                            [//2
                                                //'name' => '(ADMINISTRATOR),
                                                'username' => '20233200',
                                                'last_name' => 'Saldevar',
                                                'first_name' => 'Cristiefel',
                                                'middle_name' => 'Ramos',
                                                'suffix_name' => '',
                                                'email' => 'saldevarc@philhealth.gov.ph',
                                                'office_id' => '2',
                                                'department_id' => '4',
                                                'section_id' => '10',
                                                'password' => Hash::make('11111111'),
                                                'activated' => '1',
                                                'created_at' => Carbon::now('Asia/Manila'),                
                                            ],
            [//3
                //'name' => '(DOCUMENT CREATOR) Kit Jaca',
                'username' => '10000001',
                'last_name' => 'Jaca',
                'first_name' => 'Ma. Cristina',
                'middle_name' => 'Villar',
                'suffix_name' => '',
                'email' => 'vmjaca@up.edu.ph',
                'office_id' => '2',
                'department_id' => '7',
                'section_id' => null,
                'password' => Hash::make('11111111'),
                'activated' => '1',
                'created_at' => Carbon::now('Asia/Manila'),
            ],
                                            [//4
                                                //'name' => '(DOCUMENT CREATOR,ROUTER,PUBLISHER)',
                                                'username' => '30509314',
                                                'last_name' => 'Galanida',
                                                'first_name' => 'Tiffany',
                                                'middle_name' => 'Ramos',
                                                'suffix_name' => '',
                                                'email' => 'galanidat@philhealth.gov.ph',
                                                'office_id' => '2',
                                                'department_id' => '4',
                                                'section_id' => '11',
                                                'password' => Hash::make('11111111'),
                                                'activated' => '1',
                                                'created_at' => Carbon::now('Asia/Manila'),
                                            ],
            [//5
                //'name' => '(ROUTE CREATOR)',
                'username' => '10000002',
                'last_name' => 'Jaca',
                'first_name' => 'Jae Gabrielle',
                'middle_name' => 'Villar',
                'suffix_name' => '',
                'email' => 'virgiliojacajr@gmail.com',
                'office_id' => '2',
                'department_id' => '6',
                'section_id' => null,
                'password' => Hash::make('11111111'),
                'activated' => '1',
                'created_at' => Carbon::now('Asia/Manila'),
            ],
            [//6
                //'name' => '(ROUTER ONLY)',
                'username' => '10000003',
                'last_name' => 'Potestades',
                'first_name' => 'Ivan',
                'middle_name' => 'Alawi',
                'suffix_name' => '',
                'email' => 'jay@gmail.comxx',
                'office_id' => '1',
                'department_id' => '3',
                'section_id' => '3',
                'password' => Hash::make('11111111'),
                'activated' => '1',
                'created_at' => Carbon::now('Asia/Manila'),
            ],
            [//7
                //'name' => '(ROUTE VIEWER) Sam Armando',
                'username' => '10000004',
                'last_name' => 'Armando',
                'first_name' => 'Sam',
                'middle_name' => 'Willbe',
                'suffix_name' => '',
                'email' => 'sam@gmail.comxx',
                'office_id' => '2',
                'department_id' => '4',
                'section_id' => '12',
                'password' => Hash::make('11111111'),
                'activated' => '1',
                'created_at' => Carbon::now('Asia/Manila'),
            ],
            [//8
                //'name' => '(PUBLISHER (Document)) Ramir Agbayani',
                'username' => '10000005',
                'last_name' => 'Agbayani',
                'first_name' => 'Jamir',
                'middle_name' => 'Cain',
                'suffix_name' => '',
                'email' => 'jam@gmail.comxx',
                'office_id' => '2',
                'department_id' => '4',
                'section_id' => '11',
                'password' => Hash::make('11111111'),
                'activated' => '1',
                'created_at' => Carbon::now('Asia/Manila'),
            ],

            //------ HEAD OFFICE USER --------//
            [//9
                //'name' => '(PUBLISHER (Announcement)) Jayson Castro',  HEAD OFFICE USER 1 only
                'username' => '10000006',
                'last_name' => 'Castro',
                'first_name' => 'Jayson',
                'middle_name' => 'Manolo',
                'suffix_name' => 'Sr',
                'email' => 'paks@gmail.comxx',
                'office_id' => '1',
                'department_id' => '2',
                'section_id' => null,
                'password' => Hash::make('11111111'),
                'activated' => '1',
                'created_at' => Carbon::now('Asia/Manila'),
            ],
                                            [//10
                                                //'name' => '(ADMINISTRATOR)'
                                                'username' => '30211910',
                                                'last_name' => 'Tapawan',
                                                'first_name' => 'Igie',
                                                'middle_name' => 'Montoya',
                                                'suffix_name' => '',
                                                'email' => 'tapawani@philhealth.gov.ph',
                                                'office_id' => '1',
                                                'department_id' => '1',
                                                'section_id' => '11',
                                                'password' => Hash::make('11111111'),
                                                'activated' => '1',
                                                'created_at' => Carbon::now('Asia/Manila'),
                                            ],
            [//11
                //'name' => '(DOCUMENT CREATOR) Antonio Ducenas'
                'username' => '10000007',
                'last_name' => 'Ducenas',
                'first_name' => 'Antonio',
                'middle_name' => 'Madrigal',
                'suffix_name' => '',
                'email' => 'tonio@gmail.comxx',
                'office_id' => '1',
                'department_id' => '1',
                'section_id' => '1',
                'password' => Hash::make('11111111'),
                'activated' => '1',
                'created_at' => Carbon::now('Asia/Manila'),
            ],
                                            [//12
                                                //'name' => '(DOCUMENT CREATOR,ROUTER,PUBLISHER) '
                                                'username' => '30738319',
                                                'last_name' => 'Addun',
                                                'first_name' => 'Joan',
                                                'middle_name' => 'C',
                                                'suffix_name' => '',
                                                'email' => 'addunj@philhealth.gov.ph',
                                                'office_id' => '1',
                                                'department_id' => '1',
                                                'section_id' => '1',
                                                'password' => Hash::make('11111111'),
                                                'activated' => '1',
                                                'created_at' => Carbon::now('Asia/Manila'),
                                            ],
            [//13
                //'name' => '(ROUTE CREATOR) Paning Gavino'
                'username' => '10000008',
                'last_name' => 'Gavino',
                'first_name' => 'Paning',
                'middle_name' => 'Jimenez',
                'suffix_name' => '',
                'email' => 'gav@gmail.comxx',
                'office_id' => '1',
                'department_id' => '1',
                'section_id' => '2',
                'password' => Hash::make('11111111'),
                'activated' => '1',
                'created_at' => Carbon::now('Asia/Manila'),
            ],
            [//14
                //'name' => '(ROUTER ONLY)  '
                'username' => '10000009',
                'last_name' => 'Protacio',
                'first_name' => 'Alvin',
                'middle_name' => 'Belmonte',
                'suffix_name' => '',
                'email' => 'protacio@gmail.comxx',
                'office_id' => '1',
                'department_id' => '1',
                'section_id' => '2',
                'password' => Hash::make('11111111'),
                'activated' => '1',
                'created_at' => Carbon::now('Asia/Manila'),
            ],
            [//15
                //'name' => '(ROUTE VIEWER)  '
                'username' => '10000010',
                'last_name' => 'Rojas',
                'first_name' => 'Susan',
                'middle_name' => 'Mendoza',
                'suffix_name' => '',
                'email' => 'susan@gmail.comxx',
                'office_id' => '1',
                'department_id' => '1',
                'section_id' => '2',
                'password' => Hash::make('11111111'),
                'activated' => '1',
                'created_at' => Carbon::now('Asia/Manila'),
            ],
            [//16
                //'name' => '(PUBLISHER (Document)) '
                'username' => '10000011',
                'last_name' => 'Santos',
                'first_name' => 'Reynaldo',
                'middle_name' => 'Villanueva',
                'suffix_name' => '',
                'email' => 'rey@gmail.comxx',
                'office_id' => '1',
                'department_id' => '1',
                'section_id' => '2',
                'password' => Hash::make('11111111'),
                'activated' => '1',
                'created_at' => Carbon::now('Asia/Manila'),
            ],
        ];

        User::insert($data);
    }
}
