@php
	$description =' "Kahaki Softwares" ';

	$year = date("Y");
	
	$title = "Kahaki - ".$year. " Solution - Free MPESA integrations and more, Chat and pay solution, MPESA buttons.";
	
	$keywords = date("Y"). " - Kahaki";


$requestparams = [
    'userId' => optional(Auth::user())->id,
    'products' => $products,
    'Allproducts' => $Allproducts
];
@endphp
@extends('layouts.pos')
@section('content')
    
    <pos :requestparams="{{ json_encode($requestparams) }}"></pos>

@endsection



