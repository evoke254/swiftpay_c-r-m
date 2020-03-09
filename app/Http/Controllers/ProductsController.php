<?php

namespace App\Http\Controllers;

use App\User;
use File;
use Image;
use App\Products;
use App\History;
use App\calendar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    private $moduleName = 'products';
    private $modelName = 'App\Products';
    public function __construct()
    {
        $this->DBcolumn = $this->getColumnHeader($this->moduleName);

    }

    public function index()
    {
        
        $DBcolumn = $this->DBcolumn;
        $moduleName = $this->moduleName;
        $moduleData = Products::all();

        return view('read', compact('DBcolumn', "moduleName", "moduleData"));
    }

    public function create()
    {
        $moduleName = $this->moduleName;
        $DBcolumn = $this->DBcolumn;
        $Assigned = User::all('id', 'name')
                    ->map(function ($item){
                        $arr['value'] = $item->id;
                        $arr['text'] = $item->name;
                     return $arr;
                 })->toArray();
        $selectOptions = array('Assigned_To'=>$Assigned );
        unset($DBcolumn['Available_Stock'],
              $DBcolumn['Ordered_Stock'], 
              $DBcolumn['Units_Sold']

          );
        $name = 'Product';

        return view('create', compact('DBcolumn',"moduleName", "selectOptions", "name"));
    }

    public function store(Request $request)
    {

        $newObject = $request->all();

        if ($request->hasFile('Image')) {
            $image = $request->file('Image');
            if( ! File::exists('images/products/originals/')) {
                $org_img = File::makeDirectory(public_path('images/products/originals/'), 0777, true);
            }
            $filename = rand(10000000,99999999).time().'.'.$image->getClientOriginalExtension();
            $org_path = 'images/products/originals/' . $filename;
            Image::make($image)->resize(null, 1000, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
               })->save($org_path);
            $DB_image_path = 'images/products/originals/'.$filename;
            $newObject['Image'] = $DB_image_path;

        }
        unset($newObject['_token']); 

        $product = $newObject;    
        $insert = Products::create($newObject);
        if (!$insert) {
            return redirect()->back()->withError("Something went wrong");
        } else {
            
            $user_id = Auth::id();
            $moduleName = $this->moduleName; 
            $historyPayload = array('user_id' => $user_id, 'moduleName' => $moduleName, 'object_id' => $insert->id, 'operation' => 'Created');
            $this->log_history($historyPayload);

            return redirect($this->moduleName.'/'.$insert->id)->withSuccess($moduleName. " Saved");
        }

    }

    public function show(Products $products, $id)
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
        $hist_rltn = 'getProducts';
        $historyLogs = History::with(['getUser', $hist_rltn])
                        ->where('moduleName', $moduleName)
                        ->where('object_id', $id)
                        ->orderBy('created_at', 'desc')
                        ->get();

        $showModuleData = Products::with('getUser')
                                        ->where('id', $id)
                                        ->get()
                                        ->toArray();
        
        return view('view', compact('showModuleData' , 'DBcolumn', 'moduleName', 'historyLogs', 'hist_rltn', 'files', 'count_files', 'count_tasks'));
    }

    public function edit(Products $products, $id)
    {
        $moduleName = $this->moduleName;
        $DBcolumn = $this->DBcolumn;

            
        $Assigned = User::all('id', 'name')
                    ->map(function ($item){
                        $arr['value'] = $item->id;
                        $arr['text'] = $item->name;
                     return $arr;
                 })->toArray();
        $showModuleData = Products::with('getUser')
                        ->where('id', $id)
                        ->get()
                        ->toArray();

        $selectOptions = array('Assigned_To'=>$Assigned );
        unset($DBcolumn['Available_Stock'],
              $DBcolumn['Ordered_Stock'], 
              $DBcolumn['Units_Sold']

          );
        $name = 'Product';
        return view('edit', compact('DBcolumn', "moduleName", "selectOptions", 'showModuleData', 'name'));
    }

    public function update(Request $request, Products $products , $id)
    {
        //
        $prev_values = Products::where('id', $id)->get()->toArray(); 
        $prev_values = array_shift($prev_values);
        $updates = $request->all();
            if ($request->hasFile('Image')) {
                $image = $request->file('Image');
                if( ! File::exists('images/products/originals/')) {
                    $org_img = File::makeDirectory(public_path('images/products/originals/'), 0777, true);
                }
                $filename = rand(10000000,99999999).time().'.'.$image->getClientOriginalExtension();
                $org_path = 'images/products/originals/' . $filename;
                Image::make($image)->resize(null, 1000, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                   })->save($org_path);
                $DB_image_path = 'images/products/originals/'.$filename;
                $updates['Image'] = $DB_image_path;
            }
        unset($updates['_method'], $updates['_token']);

        $DBupdate = Products::find($id)->update($updates);
          if (!$DBupdate) {
                return redirect()->back()->withError("Something went wrong");
            } else {
            $updated_values = Products::where('id', $id)->get()->toArray();
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
    public function destroy(Products $products)
    {
        
    }
    public function getproduct(Request $request)
    {
        $id = $request->route('id');
        $product = Products::findorFail($id)->toArray();
        return response()->json($product);
    }
}
