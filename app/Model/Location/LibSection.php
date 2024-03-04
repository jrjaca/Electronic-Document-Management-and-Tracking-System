<?php

namespace App\Model\Location;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LibSection extends Model
{
    use SoftDeletes;

    public function listOfSections() //softdeleted included, location management controller. sections function
    {
        return DB::table('lib_sections')
            ->orderBy('remarks', 'ASC')
            ->orderBy('title', 'ASC')
            ->get();
    }

    public function listOfEnabledSections() //location management controller, roles.blade UserController, createDepartment & editDepartment function, except softdeleted
    {
        return DB::table('lib_sections')
            ->where('deleted_at', null)
            ->orderBy('remarks', 'ASC')
            ->orderBy('title', 'ASC')
            ->get();
    }
    
    public function storeSection($request) //location management controller, storeSection function
    {
        $section = new LibSection();
        $section->title = $request->title;
        $section->remarks = $request->remarks;
        $section->save();
    }

    public function updateSection($id, $request) //location management controller, updateSection function
    {
        $section = LibSection::findOrFail($id);
        $section->title = $request->title;
        $section->remarks = $request->remarks;
        $withUpdate = $section->isDirty();
        $section->save();

        if ($withUpdate) { return true; }
        else { return false; }
    }

    public function searchSectionById($id) //location management controller, HomeController. editSection, about function
    {
        return DB::table('lib_sections')->where('id', $id)->get();
    }

    public function disableSection($id) //location management controller, disablePermission function
    {
        LibSection::find($id)->delete();
    }

    public function enableSection($id) //location management controller, enablePermission function
    {
        LibSection::withTrashed()->where('id', $id)->restore();
    }

}
