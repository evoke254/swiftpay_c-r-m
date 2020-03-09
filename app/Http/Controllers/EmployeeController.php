<?php

namespace App\Http\Controllers;

use App\Employee;
use App\History;
use App\calendar;
use App\User;
use File;
use Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    private $moduleName = 'employees';
    public function __construct()
    {
        $this->DBcolumn = $this->getColumnHeader($this->moduleName);

    }

    public function index()
    {
        $DBcolumn = $this->DBcolumn;
        $moduleName = $this->moduleName;
        $moduleData = Employee::all()->sortByDesc('created_at');

        return view('read', compact('DBcolumn', "moduleName", "moduleData"));
    }

    public function create()
    {

        $moduleName = $this->moduleName;
        $DBcolumn = $this->DBcolumn;
        unset($DBcolumn['User'], $DBcolumn['Department']);
        
        $name = 'new record to link user to the Employee module';

        return view('create', compact('DBcolumn',"moduleName", "name"));
    }

  
    public function store(Request $request)
    {
        $newObject = $request->all();
         if ($request->hasFile('Image')) {
            $image = $request->file('Image');
            if( ! File::exists('images/'.$this->moduleName.'/originals/')) {
                $org_img = File::makeDirectory(public_path('images/'.$this->moduleName.'/originals/'), 0777, true);
            }
            $filename = rand(10000000,99999999).time().'.'.$image->getClientOriginalExtension();
            $org_path = 'images/'.$this->moduleName.'/originals/' . $filename;
            Image::make($image)->resize(null, 1000, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
               })->save($org_path);
            $DB_image_path = 'images/'.$this->moduleName.'/originals/'.$filename;
            $newObject['Image'] = $DB_image_path;

        }

        unset($newObject['_token']);
        $employee = $newObject;
        $insert = Employee::create($employee);
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

    public function show(Employee $employee)
    {

        $files = Storage::files('public/files/documents/'.$this->moduleName.'/'.$employee->id);
        $count_files = count($files);
        $count_tasks = calendar::with('getUser')
                                ->where('module_name', '=', $this->moduleName)
                                ->where('object_id', '=', $employee->id)
                                ->get()
                                ->count();
        
        $DBcolumn = $this->DBcolumn;
        $moduleName = $this->moduleName;
        $hist_rltn = 'getEmployee';

        $historyLogs = History::with(['getUser', $hist_rltn])
                                ->where('moduleName', $moduleName)
                                ->where('object_id', $employee->id)
                                ->orderBy('created_at', 'desc')
                                ->get();

        $showModuleData = Employee::with('getUser')
                                        ->where('id', $employee->id)
                                        ->get()
                                        ->toArray();
                                     //   dd($showModuleData);
        return view('view', compact('showModuleData' , 'DBcolumn', 'moduleName', 'historyLogs', 'hist_rltn', 'files', 'count_files', 'count_tasks'));
    }


    public function edit(Employee $employee)
    {
        $moduleName = $this->moduleName;
        $DBcolumn = $this->DBcolumn;

        $showModuleData = Employee::with('getUser')
                                ->where('id', $employee->id)
                                ->get()
                                ->toArray();
        $name = 'Opportunity';
        return view('edit', compact('DBcolumn', "moduleName", 'showModuleData', 'name'));
    }

    public function update(Request $request, Employee $employee)
    {
        $prev_values = Products::where('id', $id)->get()->toArray(); 
        $prev_values = array_shift($prev_values);
        $updates = $request->all();
            if ($request->hasFile('Image')) {
                $image = $request->file('Image');
                if( ! File::exists('images/'.$this->moduleName.'/originals/')) {
                    $org_img = File::makeDirectory(public_path('images/'.$this->moduleName.'/originals/'), 0777, true);
                }
                $filename = rand(10000000,99999999).time().'.'.$image->getClientOriginalExtension();
                $org_path = 'images/'.$this->moduleName.'/originals/' . $filename;
                Image::make($image)->resize(null, 1000, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                   })->save($org_path);
                $DB_image_path = 'images/'.$this->moduleName.'/originals/'.$filename;
                $updates['Image'] = $DB_image_path;
            }
        unset($updates['_method'], $updates['_token']);

        $DBupdate = Products::where('id', $id)->update($updates);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        //
    }
}
