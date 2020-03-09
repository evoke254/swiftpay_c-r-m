<?php

namespace App\Http\Controllers;

use App\chats;
use Illuminate\Http\Request;

class ModulechatsController extends Controller
{

    public function retrieve(Request $request)
    {
        //
                                
        $chatmessages = chats::with('getUser')
                                ->where('module_name', '=', $request->module_name)
                                ->where('object_id', '=', $request->object_id)
                                ->orderBy('created_at', 'desc')
                                ->get();
        return response()->json($chatmessages);
    }

    public function send(Request $request)
    {
        //
        $newObject = $request->all();
        unset($newObject['_token']);
        $insert = chats::create($newObject);
        return response()->json($insert);
    }

}

