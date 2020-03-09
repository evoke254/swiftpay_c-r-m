<?php

namespace App\Http\Controllers;

use App\History;
use App\calendar;
use App\User;


use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{

    private $moduleName = 'transactions';
    public function __construct()
    {
        $this->DBcolumn = $this->getColumnHeader($this->moduleName);

    }

    public function index()
    {
        $DBcolumn = $this->DBcolumn;
        $moduleName = $this->moduleName;
        $moduleData = Transaction::all()->sortByDesc('created_at');

        return view('read', compact('DBcolumn', "moduleName", "moduleData"));
    }

    public function show(Transaction $transaction, $id)
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

        $showModuleData = Transaction::with('getUser', 'getOpportunity', 'getInvoice', 'getClient')
                                        ->where('id', $id)
                                        ->get()
                                        ->toArray();
        return view('view', compact('showModuleData' , 'DBcolumn', 'moduleName', 'files', 'count_files', 'count_tasks'));      
    }



    public function destroy(Transaction $transaction)
    {
        //
    }
}
