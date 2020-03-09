@php
	$description =' "Kahaki Softwares" ';

	$year = date("Y");
	
	$title = "Kahaki - ".$year. " Solution - Free MPESA integrations and more, Chat and pay solution, MPESA buttons.";
	
	$keywords = date("Y"). " - Kahaki";

@endphp
@extends('layouts.pos')
@section('content')
	@include('partials.nav')

<div class="content">
  <div class="container m-0 p-0" data-animation="true" data-animation-type="fadeInUp">
    <div class="row d-flex justify-content-center">
	      <div class="col-md-4 text-center">
	        <h4 class="text-capitalize">{{$whichBill}}</h4>
	      </div>
        <div class="col-md-12">
          <a href="/pointofsales" class="btn btn-sm btn-success"><i class=" ion-ios-add-circle-outline"></i> Back to Sales</a>
        </div>
    </div>
      <div class="row w-100">
          <div class="col-sm-6 col-md-12 col-lg-12">
            <div class="table-responsive">
              <table id="kahakidt" class="table table-striped table-hover">
                <thead>
                  <tr>
                      <th class="text-nowrap" scope="col"> Subject </th>
                      <th class="text-nowrap" scope="col"> Paid Via </th>
                      <th class="text-nowrap" scope="col"> Amount </th>
                      <th class="text-nowrap" scope="col"> Status </th>
                  </tr>
                </thead>
                <tbody class="table-striped">
                  @foreach($Bills[0]['get_pos'] as $Bill)
                    <tr>
                      <td> {{$Bill['Subject']}} </td>
                      <td> {{$Bill['Pay_Via']}} </td>
                      <td> {{$Bill['Amount']}} </td>
                      <td> {{$Bill['Status']}} </td>
                    </tr>
                  @endforeach
                  </tbody>
              </table>
            </div>
          </div>
      </div>
  </div>
</div>

      

@endsection
@section('scripts')
  
  $(document).ready(function () {
    $('#kahakidt').DataTable({
        responsive: true,
        "ordering": false
    });
  });

@endsection