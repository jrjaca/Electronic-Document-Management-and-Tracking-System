<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Model\Location\LibOffice;
use App\Http\Controllers\Controller;
use App\Model\Location\UserLocation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use App\Model\Location\LibOfficeDepartment;
use App\Model\Location\LibDepartmentSection;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    //protected $redirectTo = RouteServiceProvider::HOME; //-index.blade
    protected $redirectTo = '/'; //-index.blade or index-inactive.blade

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->lib_office = new LibOffice();
        // $this->lib_office_department = new LibOfficeDepartment();
        // $this->lib_department_section = new LibDepartmentSection();
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            //'name' => ['required', 'string', 'max:255'],            
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'last_name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['required', 'string', 'max:255'],
            'office' => ['required', 'string', 'max:10'],
            'department' => ['required', 'string', 'max:10'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        if (isset($data['section'])){ $section = $data['section']; }
            else { $section = ""; } //some department have no section
            
        return User::create([
            //'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'last_name' => $data['last_name'],
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'],
            'suffix_name' => $data['suffix_name'],
            'office_id' => $data['office'],
            'department_id' => $data['department'],
            'section_id' => $section,
            'password' => Hash::make($data['password']),
        ]);

        // //user_locations has been removed
        // if ($user->exists){ //if success, save user's location
        //     //create location of user

        //     if (isset($data['section'])){ $section = $data['section']; }
        //     else { $section = ""; } //some department have no section

        //     UserLocation::create([
        //         'user_id' => $user->id,
        //         'office_id' => $data['office'],
        //         'department_id' => $data['department'],
        //         'section_id' => $section,
        //     ]);
        // }

        // return $user;
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm() //from RegisterUser.php at vendor/laravel/ui/auth-backend. The original has been bypassed
    {
        $offices = $this->lib_office->listOfEnabledOffices(); //added
        return view('auth.register', compact('offices'));
    }

}
