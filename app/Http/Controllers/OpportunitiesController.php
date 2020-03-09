<?php

namespace App\Http\Controllers;

use App\History;
use App\calendar;
use App\Lead_Source;
use App\Organization;
use App\User;
use App\Sales_Stage;
use App\Opportunities;
use App\Invoices;
use App\Quotes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Contacts;

class OpportunitiesController extends Controller
{
    private $moduleName = 'opportunities';
    public function __construct()
    {
        $this->DBcolumn = $this->getColumnHeader($this->moduleName);

    }

    public function index()
    {
        $DBcolumn = $this->DBcolumn;
        $moduleName = $this->moduleName;
        $moduleData = Opportunities::all();

        return view('read', compact('DBcolumn', "moduleName", "moduleData"));
    }
    public function create()
    {
        $moduleName = $this->moduleName;
        $DBcolumn = $this->DBcolumn;
        $Organizations = Organization::all('id', 'Organization_Name')
                        ->map(function ($item){
                            $arr['value'] = $item->id;
                            $arr['text'] = $item->name;
                         return $arr;
                     })->toArray();
        $Client = Contacts::all('id', 'Contact_Name')
                        ->map(function ($item){
                            $arr['value'] = $item->id;
                            $arr['text'] = $item->name;
                         return $arr;
                     })->toArray();
        $Lead_Sources = Lead_Source::all('id', 'name')
                        ->map(function ($item){
                            $arr['value'] = $item->id;
                            $arr['text'] = $item->name;
                         return $arr;
                     })->toArray();
        $Sales_Stages = Sales_Stage::all('id', 'name')
                        ->map(function ($item){
                            $arr['value'] = $item->id;
                            $arr['text'] = $item->name;
                         return $arr;
                     })->toArray();
        $Assigned = User::all('id', 'name')
                    ->map(function ($item){
                        $arr['value'] = $item->id;
                        $arr['text'] = $item->name;
                     return $arr;
                 })->toArray();
        $selectOptions = array('Organization' => $Organizations, 'Client_Name'=>$Client, 'Lead_Source' => $Lead_Sources, 'Sales_Stage'=> $Sales_Stages, 'Assigned_To'=>$Assigned );
        $name = 'Opportunity';

        return view('create', compact('DBcolumn',"moduleName", "selectOptions", "name"));
    }

    public function store(Request $request)
    {
        $newObject = $request->all();
     //   dd($newObject);
        unset($newObject['_token']);
        $insert = Opportunities::create($newObject);
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
    public function show(Opportunities $opportunities, $id)
    {
        $files = Storage::files('public/files/documents/'.$this->moduleName.'/'.$id);
        $count_files = count($files);
        $count_tasks = calendar::with('getUser')
                                    ->where('module_name', '=', $this->moduleName)
                                    ->where('object_id', '=', $id)
                                    ->get()
                                    ->count();
        
        $DBcolumn = $this->DBcolumn;
        $moduleName = $this->moduleName;
        $hist_rltn = 'getOpportunities';
        /*Define the relationship below ie getOpportunities in App\Hist.....*/
        $historyLogs = History::with(['getUser', $hist_rltn])
                                ->where('moduleName', $moduleName)
                                ->where('object_id', $id)
                                ->orderBy('created_at', 'desc')
                                ->get();

        $showModuleData = Opportunities::with('getUser', 'getQuotes', 'getInvoices')
                                        ->where('id', $id)
                                        ->get()
                                        ->toArray();
                                     //   dd($showModuleData);
        return view('view', compact('showModuleData' , 'DBcolumn', 'moduleName', 'historyLogs', 'hist_rltn', 'files', 'count_files', 'count_tasks'));
    }

    public function edit(Opportunities $opportunities, $id)
    {        
        $moduleName = $this->moduleName;
        $DBcolumn = $this->DBcolumn;
        $Organizations = Organization::all('id', 'Organization_Name')
                        ->map(function ($item){
                            $arr['value'] = $item->id;
                            $arr['text'] = $item->Organization_Name;
                         return $arr;
                     })->toArray();
        $Lead_Sources = Lead_Source::all('id', 'name')
                        ->map(function ($item){
                            $arr['value'] = $item->id;
                            $arr['text'] = $item->name;
                         return $arr;
                     })->toArray();
        $Sales_Stages = Sales_Stage::all('id', 'name')
                        ->map(function ($item){
                            $arr['value'] = $item->id;
                            $arr['text'] = $item->name;
                         return $arr;
                     })->toArray();
        $Assigned = User::all('id', 'name')
                    ->map(function ($item){
                        $arr['value'] = $item->id;
                        $arr['text'] = $item->name;
                     return $arr;
                 })->toArray();

        $showModuleData = Opportunities::with('getUser', 'getQuotes', 'getInvoices')
                                ->where('id', $id)
                                ->get()
                                ->toArray();
        
        $selectOptions = array('Organization' => $Organizations, 'Lead_Source' => $Lead_Sources, 'Sales_Stage'=> $Sales_Stages, 'Assigned_To'=>$Assigned );
        $name = 'Opportunity';
        return view('edit', compact('DBcolumn', "moduleName", "selectOptions", 'showModuleData', 'name'));
    }

    public function update(Request $request, Opportunities $opportunities, $id)
    {
        //
        $prev_values = Opportunities::where('id', $id)->get()->toArray(); 
        $prev_values = array_shift($prev_values);
        $updates = $request->all();
        unset($updates['_method'], $updates['_token']);

        $DBupdate = Opportunities::where('id', $id)->update($updates);
          if (!$DBupdate) {
                return redirect()->back()->withError("Something went wrong");
            } else {
            $updated_values = Opportunities::where('id', $id)->get()->toArray();
            $updated_values = array_shift($updated_values);
            unset($prev_values['created_at'], $prev_values['updated_at']);
            unset($updated_values['created_at'], $updated_values['updated_at']);
            $logArray = array_diff_assoc($prev_values, $updated_values);

                $user_id = Auth::id();
                $moduleName = $this->moduleName;
                foreach ($logArray as $key => $value) {
                    $historyPayload = array('user_id' => $user_id, 'moduleName' => $moduleName, 'object_id' => $id, 'operation' => 'Updated', 'column' => $key, 'prev_value' => $prev_values[$key], 'updated_value' => $updated_values[$key]);
                    $this->log_history($historyPayload);             
                }
                return redirect($this->moduleName.'/'.$id)->withSuccess("Record Updated");
            }

    }

    public function destroy(Opportunities $opportunities)
    {
        
    }
}
