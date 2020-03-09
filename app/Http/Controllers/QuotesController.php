<?php

namespace App\Http\Controllers;

use App\History;
use App\calendar;
use App\Quotes;
use App\Quote_Stage;
use App\Opportunities;
use App\Organization;
use App\User;
use App\Products;
use App\Invoices;
use App\Contacts;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
class QuotesController extends Controller
{
     private $moduleName = 'quotes';

    public function __construct()
    {
        $this->DBcolumn = $this->getColumnHeader($this->moduleName);
    }

    public function index()
    {
        $DBcolumn = $this->DBcolumn;
        $moduleName = $this->moduleName;
        $moduleData = Quotes::all();
        return view('read', compact('DBcolumn',"viewFields", "rawFields", "moduleName", "moduleData"));
    }


    public function create(Request $request)
    {

        $refModule = $request->route('moduleName');
        $assignee = $request->route('assignedTo');
         
         $moduleName = $this->moduleName;
         $DBcolumn = $this->DBcolumn;
         unset($DBcolumn['Amount']);
        $Opportunities = Opportunities::all('id', 'Opportunity_Name')
                                        ->map(function ($item){
                                            $arr['value'] = $item->id;
                                            $arr['text'] = $item->Opportunity_Name;
                                         return $arr;
                                     })->toArray();
        $Quote_Stage = Quote_Stage::all('id', 'name')
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
        $Organization = Organization::all('id', 'Organization_Name')
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
        $products = Products::all('id', 'Product_Name')
                                ->map(function ($item){
                                    $arr['value'] = $item->id;
                                    $arr['text'] = $item->Product_Name;
                                 return $arr;
                             })->toArray();
        $selectOptions = array('Opportunity' => $Opportunities, 'Quote_Stage'=> $Quote_Stage, 'Client'=>$Client, 'Assigned_To'=>$Assigned, 'Organization' => $Organization );
        $name = 'Quote';
        return view('quote', compact('DBcolumn',"moduleName", "selectOptions", 'name', 'products'));
    }


    public function store(Request $request)
    {        
     die('Kahaki Systems denied');

    }
    public function quotestore(Request $request)
    {        

        

        $newObject = $request->all();
        $products = json_encode($newObject['products']); 
        unset($newObject['products']);
        $insert = Quotes::create($newObject);
            if (!$insert) {
                return redirect()->back()->withError("Something went wrong");
            } else {
                $update = Quotes::findorFail($insert->id);
                $update->products = $products;
                $update->save();
                $user_id = Auth::id();
                $moduleName = $this->moduleName; 
                $historyPayload = array('user_id' => $user_id, 'moduleName' => $moduleName, 'object_id' => $insert->id, 'operation' => 'Created', 'refModule');
                $this->log_history($historyPayload);
                return response($this->moduleName.'/'.$insert->id);
            }

    }

    public function show(Quotes $quotes, $id)
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
        $hist_rltn = 'getQuotes';
        $historyLogs = History::with(['getUser', $hist_rltn])
                                ->where('moduleName', $moduleName)
                                ->where('object_id', $id)
                                ->orderBy('created_at', 'desc')
                                ->get();

        $showModuleData = Quotes::with('getUser', 'getOpportunity', 'getInvoices', 'getClient')
                                        ->where('id', $id)
                                        ->get()
                                        ->toArray();
        return view('view', compact('showModuleData' , 'DBcolumn', 'moduleName', 'historyLogs', 'hist_rltn', 'files', 'count_files', 'count_tasks'));   
    }

    public function edit(Quotes $quotes, $id)
    {
        $moduleName = $this->moduleName;
        $DBcolumn = $this->DBcolumn;
        unset($DBcolumn['Amount']);
        $Opportunities = Opportunities::all('id', 'Opportunity_Name')
                                        ->map(function ($item){
                                            $arr['value'] = $item->id;
                                            $arr['text'] = $item->Opportunity_Name;
                                         return $arr;
                                     })->toArray();
        $Quote_Stage = Quote_Stage::all('id', 'name')
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
        $Organization = Organization::all('id', 'Organization_Name')
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
        $products = Products::all('id', 'Product_Name')
                                ->map(function ($item){
                                    $arr['value'] = $item->id;
                                    $arr['text'] = $item->Product_Name;
                                 return $arr;
                             })->toArray();
        $selectOptions = array('Opportunity' => $Opportunities, 'Quote_Stage'=> $Quote_Stage, 'Client'=>$Client, 'Assigned_To'=>$Assigned, 'Organization' => $Organization );
        $name = 'Quote';

        $showModuleData = Quotes::with('getUser', 'getOpportunity', 'getInvoices', 'getClient')
                                ->where('id', $id)
                                ->get()
                                ->toArray();
        return view('edit_quote', compact('DBcolumn', "moduleName", "selectOptions", 'showModuleData', 'name', 'products'));

    }

    public function quotesUpdate(Request $request, Quotes $quotes)
    {
        $id = $request->route('id');
        $prev_values = Quotes::where('id', $id)->get()->toArray(); 
        $prev_values = array_shift($prev_values);
        $updates = $request->all();
        
        $products = json_encode($updates['products']); 
        unset($updates['products']);

        $DBupdate = Quotes::where('id', $id)->update($updates);
        $update = Quotes::findorFail($id);
                $update->products = $products;
                $update->save();

          if (!$DBupdate) {
                return response()->json("Something went wrong");
            } else {
            $updated_values = Quotes::where('id', $id)->get()->toArray();
            $updated_values = array_shift($updated_values);
            unset($prev_values['created_at'], $prev_values['updated_at']);
            unset($updated_values['created_at'], $updated_values['updated_at']);
            $logArray = array_diff_assoc($prev_values, $updated_values);

                $user_id = Auth::id();
                $moduleName = $this->moduleName;
                foreach ($logArray as $key => $value) {
                    $historyPayload = array('user_id' => $user_id, 'moduleName' => $moduleName, 'object_id' => $id, 'operation' => 'Updated', 'column' => $key);
                    $this->log_history($historyPayload);             
                }
                return response($this->moduleName.'/'.$id);
            }
    }

    public function destroy(Quotes $quotes)
    {
        //
    }
}
