<?php

namespace App\Http\Middleware;

use Closure;
use App\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class AuthGates
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
            $user = \Auth::user();

            if (!app()->runningInConsole() && $user) { //logged in

                //set allowed modules
                $roles = Role::with('permissions')->get();

                foreach ($roles as $role) {
                    foreach ($role->permissions as $permissions) {
                        $permissionsArray[$permissions->slug][] = $role->id;
                    }
                }

                foreach ($permissionsArray as $slug => $roles) {
                    
                    Gate::define($slug, function (\App\User $user) use ($roles) {
                        return count(array_intersect($user->roles->pluck('id')->toArray(), $roles)) > 0;
                    });
                }

                //check if superuser
                Gate::define('su', function ($user) {
                    $su = DB::table('role_user')
                            ->where('role_id', 1) //superuser
                            ->where('user_id', $user->id)
                            ->first();
                    if ($su === null){ return false; }
                    else { return true; }        
                });

                //user_location has been removed
                //pass the regionId of user
                // $location = DB::table('user_locations')
                //             ->where('user_id', $user->id)
                //             ->first();
                // $request->merge(array("regionId" => $location->office_id));

            }

        return $next($request);
    }
}
