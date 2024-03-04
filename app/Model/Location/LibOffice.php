<?php

namespace App\Model\Location;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LibOffice extends Model
{
    use SoftDeletes;
    
    public function listOfOffices() //softdeleted included, location management controller. offices function
    {
        return DB::table('lib_offices')
            ->orderBy('remarks', 'ASC')
            ->orderBy('title', 'ASC')
            ->get();
    }

    //softdeleted excluded, RegisterController, UserController. showRegistrationForm, listOfEnabledOffices function.
    //PublishController create, editPublishedDoc, createPublishedRoutedDoc function
    public function listOfEnabledOffices() 
    {
        return DB::table('lib_offices')
            ->where('deleted_at', null)
            ->orderBy('title', 'ASC')
            ->get();
    }

    public function searchOfficeById($id) //location managemen controller, HomeController. editOffice, about function
    {
        return DB::table('lib_offices')->where('id', $id)->get();
    }

    public function storeOfficeReturnId($request) //location management controller, storeOffice function
    {
        $data = array(
            'title' => $request['title'],
            'remarks' => $request['remarks'],
            'created_at' => Carbon::now()
        );

        return DB::table('lib_offices')->insertGetId($data);
    }

    public function updateOffice($id, $request) //location management controller, updateOffice function
    {
        $office = LibOffice::where('id', '=', $id)->first(); //softdeleted will be not found
        if ($office === null){ //if not exist or deleted
            return false;
        } 

        $office->title = $request->title;
        $office->remarks = $request->remarks;
        $withUpdate = $office->isDirty();
        $office->save();

        if ($withUpdate) { return true; }
        else { return false; }
    }

    public function disableOffice($id) //location management controller, disableOffice function
    {
        LibOffice::find($id)->delete();
    }

    public function enableOffice($id) //location management controller, enableOffice function
    {
        LibOffice::withTrashed()->where('id', $id)->restore();
    }
}
