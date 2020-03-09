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
    'csrf' => csrf_token(),
    'name' => $name,
    'selectOptions' => $selectOptions ?? 'none',
	'showModuleData' => $showModuleData ?? 'none',
];	
@endphp
@extends('layouts.app')
@section('content')
	@include('partials.nav')

    <editmodule :requestparams="{{ json_encode($requestparams) }}" ></editmodule>

@endsection
@section('scripts')
	$('.datepicker').datepicker({
		format: 'yyyy-mm-dd',
		startDate: '+0d'
	});
	
@endsection



