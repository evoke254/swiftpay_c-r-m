@php
	$description =' "Kahaki Softwares" ';

	$year = date("Y");
	
	$title = "Kahaki - ".$year. " Solution - Free MPESA integrations and more, Chat and pay solution, MPESA buttons.";
	
	$keywords = date("Y"). " - Kahaki";


$requestparams = [
    'userId' => optional(Auth::user())->id,
    'moduleName' => $moduleName ?? '0',
    'objectId' => $showModuleData['id'] ?? '0',
    'countfiles' => $count_files ?? 0,
    'counttasks' => $count_tasks ?? 0,
    'columns' => $DBcolumn,
    'name' => $name,
    'selectOptions' => $selectOptions,
    'products' => $products,
    'showModuleData' => $showModuleData,
];
@endphp
@extends('layouts.app')
@section('content')
	@include('partials.nav')
    
    <editinvoice :requestparams="{{ json_encode($requestparams) }}"></editinvoice>



@endsection



