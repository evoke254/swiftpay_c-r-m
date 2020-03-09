<?php

namespace App\Http\Controllers;

use App\Documents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DocumentsController extends Controller
{ 

    public function retrieve(Request $request)
    {
        //
        dd($module_name);
    }

    public function post(Request $request)
    {

        $module_name = $request->route('module_name');
        $objectId = $request->route('object_id');

        $file = $request->file('file');
        $path = storage_path('app/public/files/documents/'.$module_name.'/'.$objectId);
//        dd($path);
        if(!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true);
        }
        $filename = rand(1, 20).'_'.$file->getClientOriginalName();
        
        $gtpath = $file->storeAs('public/files/documents/'.$module_name.'/'.$objectId, $filename);

    }
}
