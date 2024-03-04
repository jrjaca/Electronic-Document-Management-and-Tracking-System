<?php

use App\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->delete();
        
        $data = [
            [//1
                'title' => 'Maintainer',
                'slug' => 'maintainer',
                //'description' => 'With fullaccess to user management, location management, document type management and document action management.'
                'description' => 'Manages office location, document type and document action, roles and permnissions.'
            ],
            [//2
                'title' => 'Administrator',
                'slug' => 'administrator',
                'description' => 'Can view, update, activate, disable and enable user. Can reset password of user and approve transfer of location.'
            ],
            [//3
                'title' => 'Document Creator',
                'slug' => 'document_creator', 
                'description' => 'Create new documents and send to other users.'
            ],
            [//4
                'title' => 'Document Creator, Router and Publisher',
                'slug' => 'document_router_publisher', 
                'description' => 'Create new document. With full access on routing and publishing of document.'
            ],
            [//5
                'title' => 'Route Creator',
                'slug' => 'route_creator', 
                'description' => 'With full access in document routing.'
            ],
            [//6
                'title' => 'Router Only',
                'slug' => 'router_only', 
                'description' => 'Can only released and received routed document.'
            ],
            [//7
                'title' => 'Route Viewer',
                'slug' => 'route_viewer', 
                'description' => 'Can only view information of routed document.'
            ],
            [//8
                'title' => 'Document Publisher',
                'slug' => 'document_publisher', 
                'description' => 'With full access on publishing a document.'
            ],
            [//9
                'title' => 'Announcer',
                'slug' => 'announcer', 
                'description' => 'With full access on publishing an announcement.'
            ],

/*
            [
                'title' => 'Superuser',
                'slug' => 'superuser',
                'description' => 'Can create and modify locations library, etc.'
            ],
            [
                'title' => 'Administrator',
                'slug' => 'administrator',
                'description' => 'Can add another admin at own location, manage published docs.'
            ],
            [
                'title' => 'Viewer',
                'slug' => 'viewer',
                'description' => 'Read only to all modules, including administrative modules.'
            ],
            [
                'title' => 'Creator',
                'slug' => 'creator',
                'description' => 'Create new documents, received and released., approved docs. Manager, supervisor, manage published docs, etc.'
            ],
            [
                'title' => 'Router',
                'slug' => 'router',
                'description' => 'Can only released and received documents'
            ],
            [
                'title' => 'Document Viewer',
                'slug' => 'document_viewer',
                'description' => 'Can only view documents, details and attachments.'
            ],
            [
                'title' => 'Document Trail Viewer',
                'slug' => 'trail_viewer',
                'description' => 'Can only view documents trail.'
            ],
            */
        ];

        Role::insert($data);
    }
}
