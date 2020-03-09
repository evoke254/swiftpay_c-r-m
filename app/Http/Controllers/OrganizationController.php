<?php

namespace App\Http\Controllers;

use App\Organization;
use App\User;
use App\History;
use App\calendar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrganizationController extends Controller
{

    private $moduleName = 'organizations';

    public function __construct()
    {
        $this->DBcolumn = $this->getColumnHeader($this->moduleName);

    }
    public function index()
    {
        $DBcolumn = $this->DBcolumn;
        $moduleName = $this->moduleName;
        $moduleData = Organization::all();

        return view('read', compact('DBcolumn', "moduleName", "moduleData"));
    }

  
    public function create()
    {
        $moduleName = $this->moduleName;
        $DBcolumn = $this->DBcolumn;

        unset($DBcolumn['Account_Number']);


        $Assigned = User::all('id', 'name')
                    ->map(function ($item){
                        $arr['value'] = $item->id;
                        $arr['text'] = $item->name;
                     return $arr;
                 })->toArray();
        $selectOptions = array('Assigned_To'=>$Assigned );
        $name = 'Organization';

        return view('create', compact('DBcolumn',"moduleName", "selectOptions", "name"));
    }


    public function store(Request $request)
    {
        $newObject = $request->all();
        unset($newObject['_token']);
        $insert = Organization::create($newObject);
            //$insert = DB::table($this->moduleName)->insert($newObject);
            if (!$insert) {
                return redirect()->back()->withError("Something went wrong");
            } else {
                $user_id = Auth::id();
                $moduleName = $this->moduleName; 
                $historyPayload = array('user_id' => $user_id, 'moduleName' => $moduleName, 'object_id' => $insert->id, 'operation' => 'Created');
                $this->log_history($historyPayload);

                return redirect($this->moduleName.'/'.$insert->id)->withSuccess("Saved");
            }
    }

   

    public function show(Organization $organization)
    {
        $files = Storage::files('public/files/documents/'.$this->moduleName.'/'.$organization->id);
        $count_files = count($files);
        $count_tasks = calendar::with('getUser')
                                    ->where('module_name', '=', $this->moduleName)
                                    ->where('object_id', '=', $organization->id)
                                    ->get()
                                    ->count();
        
        $DBcolumn = $this->DBcolumn;
        $moduleName = $this->moduleName;
        $hist_rltn = 'getOrganization';

        $historyLogs = History::with(['getUser', $hist_rltn])
                                ->where('moduleName', $moduleName)
                                ->where('object_id', $organization->id)
                                ->orderBy('created_at', 'desc')
                                ->get();

        $showModuleData = Organization::with('getUser', 'getQuotes', 'getInvoices', 'getContact')
                                    ->where('id', $organization->id)
                                    ->get()
                                    ->toArray();

        return view('view', compact('showModuleData' , 'DBcolumn', 'moduleName', 'historyLogs', 'hist_rltn', 'files', 'count_files', 'count_tasks'));
    }

   
    public function edit(Organization $organization)
    {
        $moduleName = $this->moduleName;
        $DBcolumn = $this->DBcolumn;
        unset($DBcolumn['Account_Number']);
        $Organizations = Organization::all('id', 'Organization_Name')
                        ->map(function ($item){
                            $arr['value'] = $item->id;
                            $arr['text'] = $item->Organization_Name;
                         return $arr;
                     })->toArray();
        $Assigned = User::all('id', 'name')
                    ->map(function ($item){
                        $arr['value'] = $item->id;
                        $arr['text'] = $item->name;
                     return $arr;
                 })->toArray();

        $showModuleData = Organization::with('getUser', 'getQuotes', 'getInvoices', 'getContact')
                                    ->where('id', $organization->id)
                                    ->get()
                                    ->toArray();
        $selectOptions = array('Assigned_To'=>$Assigned );
        $name = 'Organization';
        return view('edit', compact('DBcolumn', "moduleName", "selectOptions", 'showModuleData', 'name'));
    }

    public function update(Request $request, Organization $organization)
    {
        $prev_values = Organization::where('id', $organization->id)->get()->toArray(); 
        $prev_values = array_shift($prev_values);
        $updates = $request->all();
        unset($updates['_method'], $updates['_token']);

        $DBupdate = Organization::where('id', $organization->id)->update($updates);
          if (!$DBupdate) {
                return redirect()->back()->withError("Something went wrong");
            } else {
            $updated_values = Organization::where('id', $organization->id)->get()->toArray();
            $updated_values = array_shift($updated_values);
            unset($prev_values['created_at'], $prev_values['updated_at']);
            unset($updated_values['created_at'], $updated_values['updated_at']);
            $logArray = array_diff_assoc($prev_values, $updated_values);

                $user_id = Auth::id();
                $moduleName = $this->moduleName;
                foreach ($logArray as $key => $value) {
                    $historyPayload = array('user_id' => $user_id, 'moduleName' => $moduleName, 'object_id' => $organization->id, 'operation' => 'Updated', 'column' => $key, 'prev_value' => $prev_values[$key], 'updated_value' => $updated_values[$key]);
                    $this->log_history($historyPayload);             
                }
                return redirect($this->moduleName.'/'.$organization->id)->withSuccess("Record Updated");
            }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function destroy(Organization $organization)
    {
        //
    }
}
