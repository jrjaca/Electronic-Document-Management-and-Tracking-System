<?php

use Carbon\Carbon;
use App\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->delete();
        
        $data = [
            [//1
                'title' => 'View list of users',
                'slug' => 'user_view',
                'description' => 'user:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//2
                'title' => 'Update user info.',
                'slug' => 'user_update',
                'description' => 'user:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//3
                'title' => 'Activate user',
                'slug' => 'user_activate',
                'description' => 'user:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//4
                'title' => 'Disable user',
                'slug' => 'user_disable',
                'description' => 'user:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//5
                'title' => 'Enable user',
                'slug' => 'user_enable',
                'description' => 'user:',
                'updated_at' => null,
                'deleted_at' => null
            ],             
            [//6
                'title' => 'View list of roles',
                'slug' => 'role_view',
                'description' => 'role:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//7
                'title' => 'Add role',
                'slug' => 'role_store',
                'description' => 'role:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//8
                'title' => 'Update role',
                'slug' => 'role_update',
                'description' => 'role:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//9
                'title' => 'Disable role',
                'slug' => 'role_disable',
                'description' => 'role:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//10
                'title' => 'Enable role',
                'slug' => 'role_enable',
                'description' => 'role:',
                'updated_at' => null,
                'deleted_at' => null
            ], 
            [//11
                'title' => 'View list of permissions',
                'slug' => 'permission_view',
                'description' => 'permission:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//12
                'title' => 'Add new permission',
                'slug' => 'permission_store',
                'description' => 'permission:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//13
                'title' => 'Update permission',
                'slug' => 'permission_update',
                'description' => 'permission:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//14
                'title' => 'Disable permission',
                'slug' => 'permission_disable',
                'description' => 'permission:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//15
                'title' => 'Enable permission',
                'slug' => 'permission_enable',
                'description' => 'permission:',
                'updated_at' => null,
                'deleted_at' => null
            ], 
            [//16
                'title' => 'View list of offices',
                'slug' => 'office_view',
                'description' => 'office:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//17
                'title' => 'Add new office',
                'slug' => 'office_store',
                'description' => 'office:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//18
                'title' => 'Update office',
                'slug' => 'office_update',
                'description' => 'office:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//19
                'title' => 'Disable office',
                'slug' => 'office_disable',
                'description' => 'office:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//20
                'title' => 'Enable office',
                'slug' => 'office_enable',
                'description' => 'office:',
                'updated_at' => null,
                'deleted_at' => null
            ], 
            [//21
                'title' => 'View list of departments',
                'slug' => 'department_view',
                'description' => 'department:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//22
                'title' => 'Add new department',
                'slug' => 'department_store',
                'description' => 'department:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//23
                'title' => 'Update department',
                'slug' => 'department_update',
                'description' => 'department:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//24
                'title' => 'Disable department',
                'slug' => 'department_disable',
                'description' => 'department:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//25
                'title' => 'Enable department',
                'slug' => 'department_enable',
                'description' => 'department:',
                'updated_at' => null,
                'deleted_at' => null
            ], 
            [//26
                'title' => 'View all sections',
                'slug' => 'section_view',
                'description' => 'section:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//27
                'title' => 'Add new section',
                'slug' => 'section_store',
                'description' => 'section:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//28
                'title' => 'Update section',
                'slug' => 'section_update',
                'description' => 'section:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//29
                'title' => 'Disable section',
                'slug' => 'section_disable',
                'description' => 'section:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//30
                'title' => 'Enable section',
                'slug' => 'section_enable',
                'description' => 'section:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//31
                'title' => 'User Password Reset',
                'slug' => 'user_password_reset',
                'description' => 'user:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//32
                'title' => 'Location Transfer',
                'slug' => 'location_transfer',
                'description' => 'user:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//33
                'title' => 'Location Transfer Approval',
                'slug' => 'location_transfer_approval',
                'description' => 'user:',
                'updated_at' => null,
                'deleted_at' => null
            ], 
            [//34
                'title' => 'View document type',
                'slug' => 'documenttype_view',
                'description' => 'documenttype:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//35
                'title' => 'Add document type',
                'slug' => 'documenttype_store',
                'description' => 'documenttype:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//36
                'title' => 'Update document type',
                'slug' => 'documenttype_update',
                'description' => 'documenttype:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//37
                'title' => 'Disable document type',
                'slug' => 'documenttype_disable',
                'description' => 'documenttype:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//38
                'title' => 'Enable document type',
                'slug' => 'documenttype_enable',
                'description' => 'documenttype:',
                'updated_at' => null,
                'deleted_at' => null
            ], 
            [//39
                'title' => 'View document action',
                'slug' => 'documentaction_view',
                'description' => 'documentaction:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//40
                'title' => 'Add document action',
                'slug' => 'documentaction_store',
                'description' => 'documentaction:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//41
                'title' => 'Update document action',
                'slug' => 'documentaction_update',
                'description' => 'documentaction:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//42
                'title' => 'Disable document action',
                'slug' => 'documentaction_disable',
                'description' => 'documentaction:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//43
                'title' => 'Enable document action',
                'slug' => 'documentaction_enable',
                'description' => 'documentaction:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//44
                'title' => 'Create Routing Document',
                'slug' => 'generate_barcode',
                'description' => 'routing_management:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//45
                'title' => 'Received Document',
                'slug' => 'received_document',
                'description' => 'routing_management:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//46
                'title' => 'Released Document',
                'slug' => 'released_document',
                'description' => 'routing_management:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//47
                'title' => 'Tag As Terminal',
                'slug' => 'tag_as_terminal',
                'description' => 'routing_management:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//48
                'title' => 'Track Document',
                'slug' => 'track_document',
                'description' => 'routing_management:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//49
                'title' => 'Search Document',
                'slug' => 'search_document',
                'description' => 'document_management:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//50
                'title' => 'Create Document',
                'slug' => 'create_document',
                'description' => 'document_management: Create new, Delete draft document, view list of drafts, re-generate barcode',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//51
                'title' => 'Delete Document',
                'slug' => 'disable_document',
                'description' => 'document_management: disable only. EXCEPT DRAFT DOC',
                'updated_at' => Carbon::now('Asia/Manila'), 
                'deleted_at' => Carbon::now('Asia/Manila') 
            ],
            [//52
                'title' => 'View or Download Attachment',
                'slug' => 'view_download_attachment',
                'description' => 'document_management: View attached and download document',
                'updated_at' => Carbon::now('Asia/Manila'), 
                'deleted_at' => Carbon::now('Asia/Manila') 
            ],
            [//53
                'title' => 'Publish Document',
                'slug' => 'publish_document',
                'description' => 'publish_management:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//54
                'title' => 'View Publish Document',
                'slug' => 'view_publish_document',
                'description' => 'publish_management:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//55
                'title' => 'Unpublish Document',
                'slug' => 'unpublish_document',
                'description' => 'publish_management:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//56
                'title' => 'Update Published Document',
                'slug' => 'update_publish_document',
                'description' => 'publish_management:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//57
                'title' => 'Temporarily Delete Published Document',
                'slug' => 'temporarydelete_publish_document',
                'description' => 'publish_management:',
                'updated_at' => Carbon::now('Asia/Manila'),
                'deleted_at' => Carbon::now('Asia/Manila') 
            ],
            [//58
                'title' => 'Permanently Delete Published Document',
                'slug' => 'permanently_delete_document',
                'description' => 'publish_management:',
                'updated_at' => Carbon::now('Asia/Manila'),
                'deleted_at' => Carbon::now('Asia/Manila')
            ],
            [//59
                'title' => 'View Deleted Published Document',
                'slug' => 'view_deleted_publish_document',
                'description' => 'publish_management:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//60
                'title' => 'Publish Announcement',
                'slug' => 'publish_announcement',
                'description' => 'publish_management:',
                'updated_at' => null,
                'deleted_at' => null
            ],
            [//61
                'title' => 'Update Published Announcement',
                'slug' => 'update_publish_announcement',
                'description' => 'publish_management:',
                'updated_at' => null,
                'deleted_at' => null
            ],
        ];

        Permission::insert($data);
    }
}
