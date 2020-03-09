@php
	$description =' "Kahaki Softwares" ';

	$year = date("Y");
	
	$title = "Kahaki - ".$year. " Solution - Free MPESA integrations and more, Chat and pay solution, MPESA buttons.";
	
	$keywords = date("Y"). " - Kahaki";

@endphp
@extends('layouts.app')
@section('content')
	@include('partials.nav')

<div class="content">
  <div class="container m-0 p-0" data-animation="true" data-animation-type="fadeInUp">
    <div class="row d-flex justify-content-center">
	      <div class="col-md-4 text-center">
	        <h4 class="text-capitalize">{{$moduleName}}</h4>
	      </div>
        @if($moduleName != 'transactions')
        <div class="col-md-12">
          <a href="/{{$moduleName}}/create" class="btn btn-sm btn-success"><i class=" ion-ios-add-circle-outline"></i> Add {{$moduleName}}</a>
        </div>
        @endif
    </div>
      <div class="row w-100">
          <div class="col-sm-6 col-md-12 col-lg-12">
            <div class="table-responsive">
              <table id="kahakidt" class="table table-striped table-hover">
                <thead>
                  <tr>
                    @php $i = 0; @endphp
                    @foreach($DBcolumn as $column) 
                      @foreach($column as $tablecol)
                        <th class="text-nowrap" scope="col">{{$tablecol}}</th>
                      @endforeach
                    @php $i++; @endphp
                      @if ($i == 5)
                        @break
                      @endif
                    @endforeach
                  </tr>
                </thead>
                <tbody class="table-striped">
                  @foreach($moduleData as $datakey => $data)            
                       <tr scope="row">
                          @php $i = 0; @endphp
                            @foreach($DBcolumn as $field => $sasa)
                              <td><a class="text-dark" href="{{$moduleName}}/{{$data->id}}" class="text-default">{{$data->$field}}</a></td>
                            @php $i++; @endphp
                              @if ($i == 5)
                                @break
                              @endif
                            @endforeach
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