@extends('layouts.admin')

@section('title', 'Products List') {{-- А потом напишем trans('general.dashboard') --}}

@section('content')

<!-- BEGIN PAGE CONTENT-->
			<div class="page-content-wrapper">
				<div class="page-content">
					<!-- Begin: life time stats -->
					<div class="portlet">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-gift"></i>Products
							</div>
							<div class="actions">
								<div class="btn-group">
									<a class="btn default yellow-stripe dropdown-toggle" href="javascript:;" data-toggle="dropdown">
									<i class="fa fa-share"></i> Tools <i class="fa fa-angle-down"></i>
									</a>
									<ul class="dropdown-menu pull-right">
										<li>
											<a href="javascript:;">
											Export to Excel </a>
										</li>
										<li>
											<a href="javascript:;">
											Export to CSV </a>
										</li>
										<li>
											<a href="javascript:;">
											Export to XML </a>
										</li>
										<li class="divider">
										</li>
										<li>
											<a href="javascript:;">
											Print Invoices </a>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="portlet-body">
							<div class="table-container">
								<div class="table-actions-wrapper">
									<select class="table-group-promo-action-input form-control input-inline input-small input-sm">
										<option value="">Select...</option>
										<option value="setpromoted">Set Promo</option>
										<option value="unpromoted">Cancel Promo</option>
									</select>
									<button class="btn btn-sm yellow table-group-promo-action-submit"><i class="fa fa-check"></i> Set Promo param</button>
									<br/><br/>
									<span>
									</span>
									<select class="table-group-itemstatus-action-input form-control input-inline input-small input-sm">
										<option value="">Select...</option>
										<option value="setpublished">Publish</option>
										<option value="unpublished">Un-publish</option>
										<option value="setdeleted">Delete</option>
									</select>
									<button class="btn btn-sm yellow table-group-itemstatus-action-submit"><i class="fa fa-check"></i> Pub/Unpub param</button>
								</div>
								<table class="table table-striped table-bordered table-hover" id="datatable_products">
								<thead>
								<tr role="row" class="heading">
									<th width="1%">
										<input type="checkbox" class="group-checkable">
									</th>
									<th width="5%">
										 List №
									</th>
									<th width="5%">
										 Item ID
									</th>
									<th width="15%">
										 Product&nbsp;Name
									</th>
									<th width="10%">
										 Category
									</th>
									<th width="10%">
										 Basic Price
									</th>
									<th width="10%">
										 Date&nbsp;Created
									</th>
									<th width="10%">
										 Special&nbsp;Promo
									</th>
									<th width="10%">
										 Status
									</th>
									<th width="10%">
										 Actions
									</th>
								</tr>
								<tr role="row" class="filter">
									<td>
									</td>
									<td>
										<input type="text" class="form-control form-filter input-sm" name="item_number">
									</td>
									<td>
										<input type="text" class="form-control form-filter input-sm" name="product_id">
									</td>
									<td>
										<input type="text" class="form-control form-filter input-sm" name="product_name">
									</td>
									<td>
										@php
											$categories = \App\Models\Category::all();
										@endphp
										<select name="product_category" class="form-control form-filter input-sm">
											<option value="">Select...</option>
											 @foreach ($categories as $category)
												<option value="{!! $category->id !!}">{!! $category->name !!}</option>
											 @endforeach
										</select>
									</td>
									<td>
										<div class="margin-bottom-5">
											<input type="text" class="form-control form-filter input-sm" name="product_price_from" placeholder="From"/>
										</div>
										<input type="text" class="form-control form-filter input-sm" name="product_price_to" placeholder="To"/>
									</td>
									<td>
										<div class="input-group date date-picker margin-bottom-5" data-date-format="dd/mm/yyyy">
											<input type="text" class="form-control form-filter input-sm" readonly name="product_created_from" placeholder="From">
											<span class="input-group-btn">
											<button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
											</span>
										</div>
										<div class="input-group date date-picker" data-date-format="dd/mm/yyyy">
											<input type="text" class="form-control form-filter input-sm" readonly name="product_created_to " placeholder="To">
											<span class="input-group-btn">
											<button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
											</span>
										</div>
									</td>
									<td><!-- Is in special Promo Action ? -->
										<select id="specialpromo_filter" name="specialpromo_filter" class="form-control form-filter input-sm">
											<option value="">Select...</option>
											<option value="promoted">In spec promo</option>
											<option value="notpromoted">Not in spec promo</option>
										</select>
									</td>
									<td>
										<select id="product_status_filter" name="product_status_filter" class="form-control form-filter input-sm">
											<option value="">Select...</option>
											<option value="published">Published</option>
											<option value="unpublished">Not Published</option>
											<option value="deleted">Deleted</option>
										</select>
									</td>
									<td>
										<div class="margin-bottom-5">
											<button class="btn btn-sm yellow filter-submit margin-bottom"><i class="fa fa-search"></i> Search</button>
										</div>
										<button class="btn btn-sm red filter-cancel"><i class="fa fa-times"></i> Reset F</button>
									</td>
								</tr>
								</thead>
								<tbody>
								</tbody>
								</table>
							</div>
						</div>
					</div>
					<!-- End: life time stats -->
				</div>
			</div>
			<!-- END PAGE CONTENT-->
		</div>
	</div>
	<!-- END CONTENT -->
	
<!-- Special scripts -->
<script>
/*
        jQuery(document).ready(function() {
			
			if (localStorage['specialpromo_filter']) {
				$("#specialpromo_filter").val(localStorage['specialpromo_filter']);
			}
			
			$("#specialpromo_filter").on('change', function() { 
				localStorage['specialpromo_filter'] = this.value;
				location.reload();
			});
			
		});
*/
</script>
	
	<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="{{ asset("metronic/global/plugins/select2/select2.min.js") }}"></script>
<script type="text/javascript" src="{{ asset("metronic/global/plugins/datatables/media/js/jquery.dataTables.min.js") }}"></script>
<script type="text/javascript" src="{{ asset("metronic/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js") }}"></script>
<script type="text/javascript" src="{{ asset("metronic/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js") }}"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{ asset("metronic/global/scripts/metronic.js") }}" type="text/javascript"></script>
<script src="{{ asset("metronic/admin/layout/scripts/layout.js") }}" type="text/javascript"></script>

<script src="{{ asset("metronic/admin/layout/scripts/demo.js") }}" type="text/javascript"></script>
<script src="{{ asset("metronic/global/scripts/datatable.js") }}"></script>

<!-- Ключевой скрипт, в который надо вносить изменения -->
<script src="{{ asset("metronic/admin/pages/scripts/ecommerce-products.js") }}"></script>
<!-- END PAGE LEVEL SCRIPTS -->

<script>
        jQuery(document).ready(function() {    
           Metronic.init(); // init metronic core components
		   Layout.init(); // init current layout
		   //QuickSidebar.init(); // init quick sidebar
		   Demo.init(); // init demo features
           EcommerceProducts.init();		   
        });
</script>
<!-- END JAVASCRIPTS -->

@endsection