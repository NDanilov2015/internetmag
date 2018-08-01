@extends('layouts.admin')

@section('title', 'Dashboard - Promo Actions Settings') {{-- А потом напишем trans('general.dashboard') --}}

@section('content')
<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content">
			<!-- BEGIN PAGE HEADER-->
			<h3 class="page-title">
			Promo Action Settings: <small>choose sequence for promo-actions do</small>
			</h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="fa fa-home"></i>
						<a href="index.html">Home</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="#">Data Tables</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="#">Basic Datatables</a>
					</li>
				</ul>
				<div class="page-toolbar">
					<div class="btn-group pull-right">
						<button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
						Actions <i class="fa fa-angle-down"></i>
						</button>
						<ul class="dropdown-menu pull-right" role="menu">
							<li>
								<a href="#">Reset All Promos</a>
							</li>
							<li class="divider">
							</li>
							<li>
								<a href="#">Load Std Defaults</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">					
					<!-- BEGIN SAMPLE TABLE PORTLET-->
					<!-- action type may be promoted? -->
					<form action="{!! action('Dashboard\PromoSettingsController@update') !!}" method="POST">
					{{ csrf_field() }}
					<div class="portlet box purple">
						<div class="portlet-title">
							<div class="caption" style="width: 100%;">
								<i class="fa fa-comments"></i>Select items time sequence for promo action
								<button type="submit" class="btn btn-success pull-right">Save promo</button>
							</div>
						</div>
						<div class="portlet-body">
							<div class="table-scrollable">
								<table class="table table-bordered table-hover" id="promosettings-table">
								<thead>
								<tr>
									<th>
										 List №
									</th>
									<th>
										 Item ID
									</th>
									<th>
										 Product Name
									</th>
									<th>
										 Week №1 (1-7 days)
									</th>
									<th>
										 Week №2 (8-14 days)
									</th>
									<th>
										 Week №3 (15-21 days)
									</th>
									<th>
										 Week №4+ (22-end month)
									</th>
								</tr>
								</thead>
								<tbody>
								@foreach($promoitems as $promoitem)
									@php
										$checkboxes = $checkmaps->where('item_id', $promoitem->id)->first();
									@endphp
								<tr>
									<td>
									{{ $loop->iteration }}
									</td>
									<td>
									{{ $promoitem->id }}
									</td>
									<td>
									{{ $promoitem->name }}
									</td>
									<td class="active">
										<input type="checkbox" name="firstweek[]" value="{{ $promoitem->id }}" {{ !empty($checkboxes->firstweek) ? 'checked' : '' }}/>
									</td>
									<td class="success">
										<input type="checkbox" name="secondweek[]" value="{{ $promoitem->id }}" {{ !empty($checkboxes->secondweek) ? 'checked' : '' }} />
									</td>
									<td class="warning">
										<input type="checkbox" name="thirdweek[]" value="{{ $promoitem->id }}" {{ !empty($checkboxes->thirdweek) ? 'checked' : '' }} />
									</td>
									<td class="danger">
										<input type="checkbox" name="fourthweek[]" value="{{ $promoitem->id }}" {{ !empty($checkboxes->fourthweek) ? 'checked' : '' }} />
									</td>
								</tr>
								@endforeach
								</tbody>
								</table>
							</div>
						</div>
					</div>
					<!-- END SAMPLE TABLE PORTLET-->
					</form>
				</div>
			</div><!-- end class row -->
			<!-- END PAGE CONTENT-->
		</div>
	</div>
	<!-- END CONTENT -->
	
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{ asset("metronic/global/scripts/metronic.js") }}" type="text/javascript"></script>
<script src="{{ asset("metronic/admin/layout/scripts/layout.js") }}" type="text/javascript"></script>
<script src="{{ asset("metronic/admin/layout/scripts/demo.js") }}" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->

<script>
        jQuery(document).ready(function() {    
           Metronic.init(); // init metronic core components		   
		   Layout.init(); // init current layout
		   Demo.init(); // init demo features	   
        });
</script>

<script>
		// handle row's checkbox click
		jQuery(document).ready(function() {			
			
			let table = $('#promosettings-table');
            table.on('change', 'tbody > tr > td input[type="checkbox"]', function() {
                
				/*
					weeknumber in format:
					-firstweek, secondweek
					-thirdweek, fourthweek
				*/
				function getPromotedByWeekName(weeknumber) {
					let items = [];
					$('tbody > tr > td input[name="' + weeknumber +'"][type="checkbox"]:checked', table).each(function() {
						items.push($(this).val());
					});
					return items;
				}
				        		
				//Special internal namespace
				//console.log(the.getSelectedRows());
            });
		});
</script>
<!-- END JAVASCRIPTS -->
@endsection
