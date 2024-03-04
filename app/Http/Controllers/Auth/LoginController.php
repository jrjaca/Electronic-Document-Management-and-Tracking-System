<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

                /**
                 * Login username to be used by the controller.
                 *
                 * @var string
                 */
                protected $username;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
                $this->username = $this->findUsername();
    }

                /**
                 * Get the login username to be used by the controller.
                 *
                 * @return string
                 */
                public function findUsername()
                {
                    $login = request()->input('login');
            
                    $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            
                    request()->merge([$fieldType => $login]);
            
                    return $fieldType;
                }
            
                /**
                 * Get username property.
                 *
                 * @return string
                 */
                public function username()
                {
                    return $this->username;
                }

                /**
                 * Get the failed login response instance.
                 *
                 * @param  \Illuminate\Http\Request  $request
                 * @return \Symfony\Component\HttpFoundation\Response
                 *
                 * @throws \Illuminate\Validation\ValidationException
                 */
                protected function sendFailedLoginResponse(Request $request)
                {
                    $isDeactivatedByUsernameOrEmail = DB::table('users')
                            ->whereUsernameOrEmail($request->login, $request->login)
                            //->whereNotNull('deleted_at')
                            ->first();

                    if ($isDeactivatedByUsernameOrEmail && $isDeactivatedByUsernameOrEmail->deleted_at != null){ //account has soft deleted                        
                        throw ValidationException::withMessages([
                            $this->username() => [trans('auth.soft_deleted')],
                        ]);
                    } else {
                        throw ValidationException::withMessages([
                            $this->username() => [trans('auth.failed')],
                        ]);
                    }
                }

}
