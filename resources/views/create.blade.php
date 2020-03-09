@php
	$description =' "Kahaki Softwares" ';

	$year = date("Y");
	
	$title = "Kahaki - ".$year. " Solution - Free MPESA integrations and more, Chat and pay solution, MPESA buttons.";
	
	$column_namewords = date("Y"). " - Kahaki";


$requestparams = [
    'userId' => optional(Auth::user())->id,
    'moduleName' => $moduleName ?? '0',
    'objectId' => $showModuleData['id'] ?? '0',
    'countfiles' => $count_files ?? 0,
    'counttasks' => $count_tasks ?? 0,
    'columns' => $DBcolumn,
    'csrf' => csrf_token(),
    'name' => $name,
    'selectOptions' => $selectOptions ?? 'None',
];

@endphp

@extends('layouts.app')
@section('content')
	@include('partials.nav')
    <createmodule :requestparams="{{ json_encode($requestparams) }}" ></createmodule>
@endsection
@section('scripts')
	$('.datepicker').datepicker({
		format: 'yyyy/mm/dd',
		startDate: '+0d'
	});

	$('.custom-file-input').change(function(e){
            var fileName = e.target.files[0].name;
            $("#custom-file-label").html(fileName);
    });

@endsection



