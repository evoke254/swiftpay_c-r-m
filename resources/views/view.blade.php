@php
	$description =' "Kahaki Softwares" ';

	$year = date("Y");
	
	$title = "Kahaki - ".$year. " Solution - Free MPESA integrations and more, Chat and pay solution, MPESA buttons.";
	
	$keywords = date("Y"). " - Kahaki";



$requestparams = [
    'userId' => optional(Auth::user())->id,
    'moduleName' => $moduleName ?? '0',
    'objectId' => $showModuleData[0]['id'] ?? '0',
    'countfiles' => $count_files ?? 0,
    'counttasks' => $count_tasks ?? 0,
    $DBcolumn
];


@endphp
@extends('layouts.app')
@section('content')
    <div class="row d-flex justify-content-end mb-3">
	      <div class="col-auto mr-n4 ml-n4">
		    <a href="/{{$moduleName}}/{{$showModuleData[0]['id']}}/edit" class="btn btn-sm btn-blue-grey text-white"><i class=" ion-ios-calendar"></i> Edit </a>
	      </div>
	      <div class="col-auto">
		    <a href="/{{$moduleName}}/create" class="btn btn-sm btn-success"><i class=" ion-ios-add-circle-outline"></i> Add {{$moduleName}}</a>
	      </div>
    </div>
    <navtab-component :requestparams="{{ json_encode($requestparams) }}"></navtab-component>
  	<div class="mt-4 tab-content" id="myTabContent">
  		<div class="tab-pane fade show active"  id="Details" role="tabpanel" aria-labelledby="details-tab">	
	  		<div class="row d-flex justify-content-center">
				<div class="col">
		            <div class="text-center">
		                <h5 class="content-title">Details</h5>
		            </div>
					<div class="table-responsive">
						<table class="table table-striped">
						@foreach ($DBcolumn as $column_name => $column)
		                  	@foreach($column as $type => $display_label)
								<tr>
									<td> {{$display_label}} :
										<span class="pull-right">
							    			@if($display_label == 'Image')
    										<img class="img-responsive rounded" style="height: 90px" src='{{ asset($showModuleData[0][$column_name]) }}' alt="! not yet uploaded !">

    										@elseif($display_label == 'Assigned To')
							    			<span class="font-weight-bold"> {{$showModuleData[0]['get_user']['name']}}</span>
							    			
							    			@elseif($display_label == 'Opportunity')
							    			<a href="/opportunities/{{$showModuleData[0]['Opportunity']}}">
							    				<span class="font-weight-bold"> {{$showModuleData[0]['get_opportunity']['Opportunity_Name']}}</span>
							    			</a>
							  
							    			@elseif ($display_label == 'products' || ($display_label == 'productDetails'))
							    			 <a class="nav-link" id="products-tab" data-toggle="tab" href="#products" role="tab" aria-controls="products"  aria-selected="false"> 
								    			<span class="font-italics">Details in the products pane</span>
							    			</a>
							    			@else
							    			<span class="font-weight-bold"> {{$showModuleData[0][$column_name]}}</span>
							    			@endif
										</span>
									</td>
								</tr>
							@endforeach
						@endforeach
						</table>
					</div>
				</div>
				<formchatbox-component :requestparams="{{ json_encode($requestparams) }}"></formchatbox-component>
	  		</div>
  		</div>
  		@if(!($moduleName == 'transactions') && !($moduleName == 'employees') )
  		<div class="tab-pane fade" id="updates" role="tabpanel" aria-labelledby="updates-tab">
  			<div class="row justify-content-center">
  				<div class="col-md-12">
		            <div class="text-center">
		                <h5 class="content-title">Tracker</h5>
		            </div>
  				</div>
  				<div class="col-md-8">
  					<div class="list-group-flush">
  						@foreach($historyLogs as $log)
						  <div class="list-group-item">
						  	@if($log->operation == 'Created')
							    <p class="mb-0">
							    	<i class="fas fa-plus-circle fa-2x mr-4 text-green p-3 white-text rounded " aria-hidden="true"></i>
							    	<span class="font-weight-bold ml">{{$log->getUser->name}}</span> 
							    	created
										@foreach ($DBcolumn as $column_name => $column)
							    			<span class="font-weight-bold">{{$log->$hist_rltn->$column_name}}</span>
							    		@break
							    		@endforeach
							    </p>
							@elseif($log->operation == 'Updated')
							@php
							$column = str_replace('_', ' ', $log->column);
							@endphp
							    <p class="mb-0">
							    	<i class="fas fa-user-edit fa-2x mr-4 text-orange p-3 white-text rounded " aria-hidden="true"></i>
							    	<span class="font-weight-bold">{{$log->getUser->name}}</span> 
							    	updated
							    	<span class="font-italic">{{$column}}</span>
							    	from
							    	<span class="font-weight-light">"{{$log->prev_value}}"</span>
							    	to
							    	<span class="font-weight-bold">{{$log->updated_value}}</span>

							    </p>
							@elseif($log->operation == 'Calendar')
							    <p class="mb-0">
							    	<i class="fas fa-calendar-plus fa-2x mr-4 text-green p-3 white-text rounded " aria-hidden="true"></i>
							    	<span class="font-weight-bold">{{$log->getUser->name}}</span> 
							    	{!! $log->updated_value !!} 
							    </p>
						  	@endif
						  </div>  							
  						@endforeach
					</div>
  				</div>
  			</div>
  		</div>
  		@else
  		<div class="tab-pane fade" id="updates" role="tabpanel" aria-labelledby="updates-tab">
  			<div class="row justify-content-center">
  				<div class="col-md-12">
		            <div class="text-center">
		                <h5 class="content-title">Tracker not activated for this module</h5>
		            </div>
  				</div>
  			</div>
  		</div>
  		@endif


		<!--meetings and calls -->
  		<div class="tab-pane fade" id="tasks" role="tabpanel" aria-labelledby="tasks-tab">
  			<div class="col">
	            <div class="text-center">
	                <h5 class="content-title"> Meetings & Calls </h5>
	            </div>
			</div>
  			<div class="row justify-content-center">
  				<meetingscalls-component :requestparams="{{ json_encode($requestparams) }}"></meetingscalls-component>
			</div>
		</div>

	<!--Products -->
  		<div class="tab-pane fade" id="products" role="tabpanel" aria-labelledby="products-tab">
  			<div class="row justify-content-center">
  				<div class="col">
  					@if(!empty($showModuleData[0]['products']))
	  						<table class="table">
	  							<thead class="thead-dark">
	  								<tr>
	  									<th>Image</th>
	  									<th>Name</th>
	  									<th>Quantity</th>
	  									<th>Tax Amnt</th>
	  									<th>Selling Price</th>
	  									<th>Net Price</th>
	  								</tr>
	  							</thead>
	  							<tbody>
	  								@foreach(json_decode($showModuleData[0]['products']) as $product)
	  								@php
	  									$ttl_tax = 0;
	  									$ttl_selling = 0;
	  									$ttl_net = 0;
	  								@endphp
	  									<tr>
	  										<td>
	  											<img class="img-responsive rounded" style="height: 90px" src='{{ asset($product->product->Image) }}' alt="! not yet uploaded !">
	  										</td>
	  										<td>
	  											{{$product->product->Product_Name}} <br>
	  											{{$product->product->Description}}
	  										</td>
	  										<td>
	  											{{$product->quantity}}
	  										</td>
	  										<td>
	  											{{$product->taxamnt}}
	  											@php $ttl_tax += $product->taxamnt; @endphp
	  										</td>
	  										<td>
	  											{{$product->product->Selling_Price}}
	  											@php $ttl_selling += $product->product->Selling_Price; @endphp
	  										</td>
	  										<td>
	  											{{$product->net}}
	  											@php $ttl_net += $product->net; @endphp
	  										</td>
	  									</tr>
	  								@endforeach
	  								<tr class="float-right">
	  									<thead class="thead-dark">
			  								<tr>
			  									<th></th>
			  									<th></th>
			  									<th>Totals</th>
			  									<th>Kshs. {{ $ttl_tax }}</th>
			  									<th>Kshs. {{$ttl_selling}}</th>
			  									<th>Kshs. {{$ttl_net}}</th>
			  								</tr>
			  							</thead>
	  								</tr>
	  							</tbody>
	  						</table>
	  				@else
	  				<p class="content-title">There are no linked products. Generate a Quote or invoice. </p>
	  				@endif
  				</div>
  			</div>
  		</div>
  	<!--Documents -->
  		<div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
  			<div class="row justify-content-center">
  				<div class="col-md-12">
		            <div class="text-center">
		                <h5 class="content-title">Documents </h5>
		            </div>
  				</div>
  					@foreach ($files as $key => $file)
			            @php
			                $path = explode('/', $file, 2);
			                $path = end($path);
			            @endphp
			            <div class="col-md-6 text-center">
			            	<iframe class="rounded" src='{{ asset("storage/".$path)  }}'></iframe> <br>
			            	<button type="button" class="btn btn-success btn-md" data-toggle="modal" data-target="#showPdf{{$key}}"> Expand</button>	
			            </div>
						<div class="modal fade right" id="showPdf{{$key}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog modal-lg modal-full-height modal-right" role="document">
							    <div class="modal-content">
							      <div class="modal-header">
							        <h4 class="modal-title w-100" id="myModalLabel">Modal title</h4>
							        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							          <span aria-hidden="true">&times;</span>
							        </button>
							      </div>
							      <div class="modal-body">
        			            	<iframe class="w-100" style="height: 700px" src='{{ asset("storage/".$path)  }}'></iframe>
							      </div>
							      <div class="modal-footer justify-content-center">
							        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							        <button type="button" class="btn btn-primary">Save changes</button>
							      </div>
							    </div>
							  </div>
							</div>
							<!-- Full Height Modal Right -->
  					@endforeach

  				<dropzone :requestparams="{{ json_encode($requestparams) }}"></dropzone>
  			</div>
  		</div>
  	<!--quote -->
  	@if(!($moduleName == 'quotes'))
  		<div class="tab-pane fade" id="quote" role="tabpanel" aria-labelledby="quote-tab">
  			<div class="row justify-content-center">
  				<div class="col-md-12 text-left">
  					<a href="/quotes/create">
  					<i class="fas fa-plus-circle fa-2x mr-4 text-green p-3 white-text rounded hoverable myPointer mb-3" style="font-size: 50px" aria-hidden="true"></i>
  					</a>
  				</div>
				@if(empty($showModuleData[0]['get_quotes']))
	  				<div class="col-md-12">
		                <h6 class="content-title">There are no linked quotes</h6>
	  				</div>
  				@else
  				<div class="col-md-12">
  						<div class="table-responsive">
						<table class="table table-striped">
							<thead class="thead-dark">
  								<tr>
  									<th>Quote Subject</th>
  									<th>Description</th>
  									<th>Quote Stage</th>
  									<th>Amount</th>
  									<th>More ...</th>
  								</tr>
  							</thead>
		                  	@foreach($showModuleData[0]['get_quotes'] as $quotes)
								<tr>
									<td> {{$quotes['Quote_Subject']}} 	</td>
									<td>{{$quotes['Description']}} ha</td>
									<td>{{$quotes['Quote_Stage']}}</td>
									<td>Kshs. {{$quotes['Amount']}}</td>
									<td> <a href="/quotes/{{$quotes['id']}}" class="btn btn-sm btn-warning">
										Details ..
										</a>
									</td>
								</tr>
							@endforeach
						</table>
					</div>
  				</div>
  				@endif
  			</div>
  		</div>
  	@endif
  	@if(!($moduleName == 'invoices'))
  	<!--invoice -->
  		<div class="tab-pane fade" id="invoice" role="tabpanel" aria-labelledby="invoice-tab">
  			<div class="row justify-content-center">
  				<div class="col-md-12 text-left">
  					<a href="/invoices/create">
  					<i class="fas fa-plus-circle fa-2x mr-4 text-green p-3 white-text rounded hoverable myPointer mb-3" style="font-size: 50px" aria-hidden="true"></i>
  					</a>
  				</div>
				@if(empty($showModuleData[0]['get_invoices']))
	  				<div class="col-md-12">
		                <h6 class="content-title">There are no invoices</h6>
	  				</div>
	  			@else
  				<div class="col-md-12">
  						<div class="table-responsive">
						<table class="table table-striped">
							<thead class="thead-dark">
  								<tr>
  									<th>Invoice Subject</th>
  									<th>Description</th>
  									<th>Invoice Stage</th>
  									<th>Amount</th>
  									<th>More ...</th>
  								</tr>
  							</thead>
		                  	@foreach($showModuleData[0]['get_invoices'] as $invoice)
								<tr>
									<td> {{$invoice['Invoice_Subject']}} 	</td>
									<td>{{$invoice['Description']}} ha</td>
									<td>{{$invoice['Invoice_Stage']}}</td>
									<td>Kshs. {{$invoice['Amount']}}</td>
									<td> <a href="/invoices/{{$invoice['id']}}" class="btn btn-sm btn-warning">
										Details ..
										</a>
									</td>
								</tr>
							@endforeach
						</table>
					</div>
  				</div>
  				@endif
  			</div>
  		</div>
  	@endif
  	<!--mails -->
  		<div class="tab-pane fade" id="mails" role="tabpanel" aria-labelledby="mails-tab">
  			<div class="row justify-content-center">
  				<div class="col">
		            <div class="text-center">
		                <h5 class="content-title">Mails </h5>
		            </div>
  				</div>
  			</div>
  		</div>
  	<!-- contact -->
  		<div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
  			<div class="row justify-content-center">
  				<div class="col">
		            <div class="text-center">
		                <h5 class="content-title">Contacts </h5>
		            </div>
  				</div>
  			</div>
  		</div>

  		<!-- Done with nav -->
	</div>



	<!-- Done -->
  	<div class="col-sm-12 col-md-12 col-lg-12"><hr></div>


@endsection





