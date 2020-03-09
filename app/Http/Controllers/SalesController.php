<?php

namespace App\Http\Controllers;

use App\Sales;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    private $moduleName = 'sales';

    public function __construct()
    {
        $this->fields = $this->getColumnHeader($this->moduleName);
    }

    public function index()
    {
        $fields = $this->fields;
        $viewFields = $fields[0];
        $rawFields = $fields[1];
        $moduleName = $this->moduleName;
        $moduleData = Sales::all();
        return view('read', compact("viewFields", "rawFields", "moduleName", "moduleData"));
    }

 
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Sales $sales)
    {
        //
    }

    public function edit(Sales $sales)
    {
        //
    }


    public function update(Request $request, Sales $sales)
    {
        //
    }

    public function destroy(Sales $sales)
    {
        //
    }
}
