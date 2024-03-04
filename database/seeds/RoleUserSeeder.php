<?php

use App\RoleUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role_users')->delete();
        
        $data = [
            //------ HEAD OFFICE USER --------//
            [
                'user_id' => '1',   //1 user only
                'role_id' => '1',   //SUPERUSER/USER MANAGER/MAINTAINER
            ],

            //------ PRO NCR USER --------//
            [
                'user_id' => '2', 
                'role_id' => '2',   //ADMINISTRATOR
            ],
            [
                'user_id' => '3', 
                'role_id' => '3',   //DOCUMENT CREATOR
            ],
            [
                'user_id' => '4', 
                'role_id' => '4',   //DOCUMENT CREATOR,ROUTER,PUBLISHER
            ],
            [
                'user_id' => '5', 
                'role_id' => '5',   //ROUTE CREATOR
            ],
            [
                'user_id' => '6', 
                'role_id' => '6',   //ROUTER ONLY
            ],
            [
                'user_id' => '7', 
                'role_id' => '7',   //ROUTE VIEWER
            ],
            [
                'user_id' => '8', 
                'role_id' => '8',   //PUBLISHER (Document)
            ],

            //------ HEAD OFFICE USER --------//
            [
                'user_id' => '9',   //1 user only
                'role_id' => '9',   //PUBLISHER (Announcement)
            ],
            [
                'user_id' => '10', 
                'role_id' => '2',   //ADMINISTRATOR
            ],
            [
                'user_id' => '11', 
                'role_id' => '3',   //DOCUMENT CREATOR
            ],
            [
                'user_id' => '12', 
                'role_id' => '4',   //DOCUMENT CREATOR,ROUTER,PUBLISHER
            ],
            [
                'user_id' => '13', 
                'role_id' => '5',   //ROUTE CREATOR
            ],
            [
                'user_id' => '14', 
                'role_id' => '6',   //ROUTER ONLY
            ],
            [
                'user_id' => '15', 
                'role_id' => '7',   //ROUTE VIEWER
            ],
            [
                'user_id' => '16', 
                'role_id' => '8',   //PUBLISHER (Document)
            ],
        ];

        RoleUser::insert($data);
    }
}
