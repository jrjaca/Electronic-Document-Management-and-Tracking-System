<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    public function updateRole($request) //UserController. updateUser function
    {
        DB::table('role_user')
            ->where('user_id', $request->user_id_e)
            ->update(['role_id' => $request->role_e]);
    }
}
