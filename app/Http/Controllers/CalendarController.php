<?php

namespace App\Http\Controllers;

use App\calendar;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    

    public function retrieve(Request $request)
    {

        $usercalendar = calendar::with('getUser')
                        ->where('module_name', '=', $request->module_name)
                        ->where('object_id', '=', $request->object_id)
                        ->get();
        return response()->json($usercalendar);
    }

    public function retrieveSelected(Request $request)
    {
        $usercalendar = calendar::with('getUser')
                        ->where('id', '=', $request->selectedEvent)
                        ->get();
        return response()->json($usercalendar);
    }
    public function post(Request $request)
    {
        $newObject = $request->all();
        if (!$request->update) {
            unset($newObject['_token']);
            $insert = calendar::create($newObject);
             if (!$insert) {
                return response($insert);
            } else {
                $user_id = $request->User_id;
                $moduleName = $request->moduleName;
                $start = date('D dS M Y', strtotime($request->start)); 
                $historyPayload = array('user_id' => $user_id, 'moduleName' => $request->module_name, 'object_id' => $request->object_id, 'operation' => 'Calendar', 'updated_value' => 'created task <span class="font-weight-bold">'.$request->title.'</span> happening on '.$start.' at <span class="font-weight-bold">'.$request->location.'</span> ');
                $this->log_history($historyPayload);

                return $this->retrieve($request);
            }
        } elseif ($request->update == 'true') {
            unset($newObject['_token'], $newObject['update'], $newObject['id']);
            $DBupdate = Calendar::where('id', $request->id)->update($newObject);
             if (!$DBupdate) {
                return response("Something went wrong");
            } else {
                $user_id = $request->User_id;
                $moduleName = $request->moduleName;
                $start = date('D dS M Y', strtotime($request->start)); 
                $historyPayload = array('user_id' => $user_id, 'moduleName' => $request->module_name, 'object_id' => $request->object_id, 'operation' => 'Calendar', 'updated_value' => 'updated the task <span class="font-weight-bold">'.$request->title.'</span> happening on '.$start.' at <span class="font-italic">'.$request->location.'</span> </br> <span class="text-center">Details: <span class="font-weight-bold">'.$request->details.'</span></span> ');
                $this->log_history($historyPayload);

                return $this->retrieve($request);
            }
        }
    }

    public function counttask(Request $request)
    {
        $count_tasks = calendar::with('getUser')
                                    ->where('module_name', '=', $request->module_name)
                                    ->where('object_id', '=', $request->object_id)
                                    ->get()
                                    ->count();
        return response($count_tasks);
    }



    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(calendar $calendar)
    {
        //
    }
    public function edit(calendar $calendar)
    {
        //
    }
    public function update(Request $request, calendar $calendar)
    {
        //
    }
    public function destroy(calendar $calendar)
    {
        //
    }
}
