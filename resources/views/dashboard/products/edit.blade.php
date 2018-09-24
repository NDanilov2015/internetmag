@extends('layouts.admin')

@section('title', 'Edit Product') {{-- А потом напишем trans('general.dashboard') --}}

@section('content')
<!-- BEGIN PAGE HEADER-->
<h3 class="page-title">
Product Edit <small>create & edit product</small>
</h3>
<div class="page-bar">
	<ul class="page-breadcrumb">
		<li>
			<i class="fa fa-home"></i>
			<a href="index.html">Home</a>
			<i class="fa fa-angle-right"></i>
		</li>
		<li>
			<a href="#">eCommerce</a>
			<i class="fa fa-angle-right"></i>
		</li>
		<li>
			<a href="#">Product Edit</a>
		</li>
	</ul>
	<div class="page-toolbar">
		<div class="btn-group pull-right">
			<button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true">
			Actions <i class="fa fa-angle-down"></i>
			</button>
			<ul class="dropdown-menu pull-right" role="menu">
				<li>
					<a href="#">Action</a>
				</li>
				<li>
					<a href="#">Another action</a>
				</li>
				<li>
					<a href="#">Something else here</a>
				</li>
				<li class="divider">
				</li>
				<li>
					<a href="#">Separated link</a>
				</li>
			</ul>
		</div>
	</div>
</div>
<!-- END PAGE HEADER-->

<!-- BEGIN PAGE CONTENT-->
<div class="row">
	<div class="col-md-12">
		<form class="form-horizontal form-row-seperated" method="POST">
			{{ csrf_field() }}
			<div class="portlet">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-shopping-cart"></i>Test Product
					</div>
					<div class="actions btn-set">
						<!--
						<a class="btn btn-default" name="back" href="{{ action('Dashboard\ItemsController@index') }}"><i class="fa fa-angle-left"></i>Cancel and Back</a>
						-->
						<a class="btn red" href="{{ action('Dashboard\ItemsController@index') }}"><i class="fa fa-reply"></i> Cancel and Back</a>
						<button type="submit" class="btn green" formaction="{{ action('Dashboard\ItemsController@update', ['id' => $item->id ]) }}" name="operation_button" value="save"><i class="fa fa-check"></i> Save</button>
						<button type="submit" class="btn green" formaction="{{ action('Dashboard\ItemsController@update', ['id' => $item->id]) }}" name="operation_button" value = "save_and_continue"><i class="fa fa-check-circle"></i> Save & Continue Edit</button>
						<div class="btn-group">
							<a class="btn yellow dropdown-toggle" href="javascript:;" data-toggle="dropdown">
							<i class="fa fa-share"></i> More <i class="fa fa-angle-down"></i>
							</a>
							<ul class="dropdown-menu pull-right">
								<li>
									<a href="javascript:;">
									Duplicate </a>
								</li>
								<li>
									<a href="javascript:;">
									Delete </a>
								</li>
								<li class="divider">
								</li>
								<li>
									<a href="javascript:;">
									Print </a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="portlet-body">
					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active">
								<a href="#tab_general" data-toggle="tab">
								General </a>
							</li>
							<li>
								<a href="#tab_meta" data-toggle="tab">
								Meta </a>
							</li>
							<li>
								<a href="#tab_images" data-toggle="tab">
								Images </a>
							</li>
						<!--
							<li>
								<a href="#tab_reviews" data-toggle="tab">
								Reviews <span class="badge badge-success">
								3 </span>
								</a>
							</li>
							<li>
								<a href="#tab_history" data-toggle="tab">
								History </a>
							</li>
						-->
						</ul>
						<div class="tab-content no-space">
							<div class="tab-pane active" id="tab_general">
								<div class="form-body">
									<div class="form-group">
										<label class="col-md-2 control-label">Name: <span class="required">
										* </span>
										</label>
										<div class="col-md-10">
											<input type="text" class="form-control" name="product[name]" placeholder="" value="{{ $item->name }}" >
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-2 control-label">Description: <span class="required">
										* </span>
										</label>
										<div class="col-md-10">
											<textarea class="form-control" name="product[description]">{{ $item->description }}</textarea>
										</div>
									</div>
									<!--
									<div class="form-group">
										<label class="col-md-2 control-label">Short Description: <span class="required">
										* </span>
										</label>
										<div class="col-md-10">
											<textarea class="form-control" name="product[short_description]"></textarea>
											<span class="help-block">
											shown in product listing </span>
										</div>
									</div>
									-->
									<div class="form-group">
										<label class="col-md-2 control-label">Categories: <span class="required">
										* </span>
										</label>
										<div class="col-md-10">
											<div class="form-control height-auto">
												<div class="scroller" style="height:275px;" data-always-visible="1">
													@php
														$categories = \App\Models\Category::all();
													@endphp
													<ul class="list-unstyled">
														@foreach($categories as $category)
															@php
																$checked = null;
																foreach ($item_categories as $item_category) {
																	if ($item_category->id == $category->id) {
																		$checked = 'checked';
																	}
																}
															@endphp
														<li>
															<label><input type="checkbox" name="product[categories][]" value="{{ $category->id }}" {{ $checked }}>{{ $category->name }}</label>
														</li>
														@endforeach
													</ul>
												</div>
											</div>
											<span class="help-block">
											select one or more categories </span>
										</div>
									</div>
									<!--
									<div class="form-group">
										<label class="col-md-2 control-label">Available Date: <span class="required">
										* </span>
										</label>
										<div class="col-md-10">
											<div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
												<input type="text" class="form-control" name="product[available_from]">
												<span class="input-group-addon">
												to </span>
												<input type="text" class="form-control" name="product[available_to]">
											</div>
											<span class="help-block">
											availability daterange. </span>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-2 control-label">SKU: <span class="required">
										* </span>
										</label>
										<div class="col-md-10">
											<input type="text" class="form-control" name="product[sku]" placeholder="">
										</div>
									</div>
									-->
									<div class="form-group">
										<label class="col-md-2 control-label">Price, USD: <span class="required">
										* </span>
										</label>
										<div class="col-md-10">
											<input type="text" class="form-control" name="product[price]" placeholder="" value="{{ number_format($item->price/100, 2, '.', '') }}">
										</div>
									</div>
									<!--
									<div class="form-group">
										<label class="col-md-2 control-label">Tax Class: <span class="required">
										* </span>
										</label>
										<div class="col-md-10">
											<select class="table-group-action-input form-control input-medium" name="product[tax_class]">
												<option value="">Select...</option>
												<option value="1">None</option>
												<option value="0">Taxable Goods</option>
												<option value="0">Shipping</option>
												<option value="0">USA</option>
											</select>
										</div>
									</div>
									-->
									<div class="form-group">
										<label class="col-md-2 control-label">Status: <span class="required">
										* </span>
										</label>
										<div class="col-md-10">
											<select class="table-group-action-input form-control input-medium" name="product[status]">
												<option value="">Select...</option>
												<option value="published"  @if($item->item_status == 'published') selected @endif>Published</option>
												<option value="unpublished" @if($item->item_status == 'unpublished') selected @endif>Not Published</option>
												<option value="deleted" @if($item->item_status == 'deleted') selected @endif>Deleted</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane" id="tab_meta">
								<div class="form-body">
									<div class="form-group">
										<label class="col-md-2 control-label">Meta Title:</label>
										<div class="col-md-10">
											<input type="text" class="form-control maxlength-handler" name="product[meta_title]" maxlength="100" placeholder="" value="{{ $item->meta_title }}">
											<span class="help-block">
											max 100 chars </span>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-2 control-label">Meta Keywords:</label>
										<div class="col-md-10">
											<textarea class="form-control maxlength-handler" rows="8" name="product[meta_keywords]" maxlength="1000">{{ $item->meta_keywords }}</textarea>
											<span class="help-block">
											max 1000 chars </span>
										</div>
									</div>
									<div class="form-group">
										<label class="col-md-2 control-label">Meta Description:</label>
										<div class="col-md-10">
											<textarea class="form-control maxlength-handler" rows="8" name="product[meta_description]" maxlength="255">{{ $item->meta_description }}</textarea>
											<span class="help-block">
											max 255 chars </span>
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane" id="tab_images">
								<div class="alert alert-success margin-bottom-10">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
									<i class="fa fa-warning fa-lg"></i> Image type and information need to be specified.
								</div>
								<div id="tab_images_uploader_container" class="text-align-reverse margin-bottom-10">
									<a id="tab_images_uploader_pickfiles" href="javascript:;" class="btn yellow">
									<i class="fa fa-plus"></i> Select Files </a>
									<a id="tab_images_uploader_uploadfiles" href="javascript:;" class="btn green">
									<i class="fa fa-share"></i> Upload Files </a>
								</div>
								<div class="row">
									<div id="tab_images_uploader_filelist" class="col-md-6 col-sm-12">
									</div>
								</div>
								<table class="table table-bordered table-hover">
								<thead>
								<tr role="row" class="heading">
									<th width="8%">
										 Image
									</th>
									<th width="25%">
										 Label
									</th>
									<th width="8%">
										 Sort Order
									</th>
									<th width="10%">
										 Base Image
									</th>
									<th width="10%">
										 Small Image
									</th>
									<th width="10%">
										 Thumbnail
									</th>
									<th width="10%">
									</th>
								</tr>
								</thead>
								<tbody>
								<tr>
									<td>
										<a href="../../assets/admin/pages/media/works/img1.jpg" class="fancybox-button" data-rel="fancybox-button">
										<img class="img-responsive" src="../../assets/admin/pages/media/works/img1.jpg" alt="">
										</a>
									</td>
									<td>
										<input type="text" class="form-control" name="product[images][1][label]" value="Thumbnail image">
									</td>
									<td>
										<input type="text" class="form-control" name="product[images][1][sort_order]" value="1">
									</td>
									<td>
										<label>
										<input type="radio" name="product[images][1][image_type]" value="1">
										</label>
									</td>
									<td>
										<label>
										<input type="radio" name="product[images][1][image_type]" value="2">
										</label>
									</td>
									<td>
										<label>
										<input type="radio" name="product[images][1][image_type]" value="3" checked>
										</label>
									</td>
									<td>
										<a href="javascript:;" class="btn default btn-sm">
										<i class="fa fa-times"></i> Remove </a>
									</td>
								</tr>
								<tr>
									<td>
										<a href="../../assets/admin/pages/media/works/img2.jpg" class="fancybox-button" data-rel="fancybox-button">
										<img class="img-responsive" src="../../assets/admin/pages/media/works/img2.jpg" alt="">
										</a>
									</td>
									<td>
										<input type="text" class="form-control" name="product[images][2][label]" value="Product image #1">
									</td>
									<td>
										<input type="text" class="form-control" name="product[images][2][sort_order]" value="1">
									</td>
									<td>
										<label>
										<input type="radio" name="product[images][2][image_type]" value="1">
										</label>
									</td>
									<td>
										<label>
										<input type="radio" name="product[images][2][image_type]" value="2" checked>
										</label>
									</td>
									<td>
										<label>
										<input type="radio" name="product[images][2][image_type]" value="3">
										</label>
									</td>
									<td>
										<a href="javascript:;" class="btn default btn-sm">
										<i class="fa fa-times"></i> Remove </a>
									</td>
								</tr>
								<tr>
									<td>
										<a href="../../assets/admin/pages/media/works/img3.jpg" class="fancybox-button" data-rel="fancybox-button">
										<img class="img-responsive" src="../../assets/admin/pages/media/works/img3.jpg" alt="">
										</a>
									</td>
									<td>
										<input type="text" class="form-control" name="product[images][3][label]" value="Product image #2">
									</td>
									<td>
										<input type="text" class="form-control" name="product[images][3][sort_order]" value="1">
									</td>
									<td>
										<label>
										<input type="radio" name="product[images][3][image_type]" value="1" checked>
										</label>
									</td>
									<td>
										<label>
										<input type="radio" name="product[images][3][image_type]" value="2">
										</label>
									</td>
									<td>
										<label>
										<input type="radio" name="product[images][3][image_type]" value="3">
										</label>
									</td>
									<td>
										<a href="javascript:;" class="btn default btn-sm">
										<i class="fa fa-times"></i> Remove </a>
									</td>
								</tr>
								</tbody>
								</table>
							</div>
							<!--
							<div class="tab-pane" id="tab_reviews">
								<div class="table-container">
									<table class="table table-striped table-bordered table-hover" id="datatable_reviews">
									<thead>
									<tr role="row" class="heading">
										<th width="5%">
											 Review&nbsp;#
										</th>
										<th width="10%">
											 Review&nbsp;Date
										</th>
										<th width="10%">
											 Customer
										</th>
										<th width="20%">
											 Review&nbsp;Content
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
											<input type="text" class="form-control form-filter input-sm" name="product_review_no">
										</td>
										<td>
											<div class="input-group date date-picker margin-bottom-5" data-date-format="dd/mm/yyyy">
												<input type="text" class="form-control form-filter input-sm" readonly name="product_review_date_from" placeholder="From">
												<span class="input-group-btn">
												<button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
												</span>
											</div>
											<div class="input-group date date-picker" data-date-format="dd/mm/yyyy">
												<input type="text" class="form-control form-filter input-sm" readonly name="product_review_date_to" placeholder="To">
												<span class="input-group-btn">
												<button class="btn btn-sm default" type="button"><i class="fa fa-calendar"></i></button>
												</span>
											</div>
										</td>
										<td>
											<input type="text" class="form-control form-filter input-sm" name="product_review_customer">
										</td>
										<td>
											<input type="text" class="form-control form-filter input-sm" name="product_review_content">
										</td>
										<td>
											<select name="product_review_status" class="form-control form-filter input-sm">
												<option value="">Select...</option>
												<option value="pending">Pending</option>
												<option value="approved">Approved</option>
												<option value="rejected">Rejected</option>
											</select>
										</td>
										<td>
											<div class="margin-bottom-5">
												<button class="btn btn-sm yellow filter-submit margin-bottom"><i class="fa fa-search"></i> Search</button>
											</div>
											<button class="btn btn-sm red filter-cancel"><i class="fa fa-times"></i> Reset</button>
										</td>
									</tr>
									</thead>
									<tbody>
									</tbody>
									</table>
								</div>
							</div>
							<div class="tab-pane" id="tab_history">
								<div class="table-container">
									<table class="table table-striped table-bordered table-hover" id="datatable_history">
									<thead>
									<tr role="row" class="heading">
										<th width="25%">
											 Datetime
										</th>
										<th width="55%">
											 Description
										</th>
										<th width="10%">
											 Notification
										</th>
										<th width="10%">
											 Actions
										</th>
									</tr>
									<tr role="row" class="filter">
										<td>
											<div class="input-group date datetime-picker margin-bottom-5" data-date-format="dd/mm/yyyy hh:ii">
												<input type="text" class="form-control form-filter input-sm" readonly name="product_history_date_from" placeholder="From">
												<span class="input-group-btn">
												<button class="btn btn-sm default date-set" type="button"><i class="fa fa-calendar"></i></button>
												</span>
											</div>
											<div class="input-group date datetime-picker" data-date-format="dd/mm/yyyy hh:ii">
												<input type="text" class="form-control form-filter input-sm" readonly name="product_history_date_to" placeholder="To">
												<span class="input-group-btn">
												<button class="btn btn-sm default date-set" type="button"><i class="fa fa-calendar"></i></button>
												</span>
											</div>
										</td>
										<td>
											<input type="text" class="form-control form-filter input-sm" name="product_history_desc" placeholder="To"/>
										</td>
										<td>
											<select name="product_history_notification" class="form-control form-filter input-sm">
												<option value="">Select...</option>
												<option value="pending">Pending</option>
												<option value="notified">Notified</option>
												<option value="failed">Failed</option>
											</select>
										</td>
										<td>
											<div class="margin-bottom-5">
												<button class="btn btn-sm yellow filter-submit margin-bottom"><i class="fa fa-search"></i> Search</button>
											</div>
											<button class="btn btn-sm red filter-cancel"><i class="fa fa-times"></i> Reset</button>
										</td>
									</tr>
									</thead>
									<tbody>
									</tbody>
									</table>
								</div>
							</div>
							-->
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<!-- END PAGE CONTENT-->

<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="{{ asset("metronic/global/plugins/select2/select2.min.js") }}"></script>
<script type="text/javascript" src="{{ asset("metronic/global/plugins/datatables/media/js/jquery.dataTables.min.js") }}"></script>
<script type="text/javascript" src="{{ asset("metronic/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js") }}"></script>
<script type="text/javascript" src="{{ asset("metronic/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js") }}"></script>
<script type="text/javascript" src="{{ asset("metronic/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js") }}"></script>
<script type="text/javascript" src="{{ asset("metronic/global/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js") }}" ></script>
<script type="text/javascript" src="{{ asset("metronic/global/plugins/bootstrap-touchspin/bootstrap.touchspin.js") }}"></script>
<script type="text/javascript" src="{{ asset("metronic/global/plugins/fancybox/source/jquery.fancybox.pack.js") }}"></script>
<script type="text/javascript" src="{{ asset("metronic/global/plugins/plupload/js/plupload.full.min.js") }}"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{ asset("metronic/global/scripts/metronic.js") }}" type="text/javascript"></script>
<script src="{{ asset("metronic/admin/layout/scripts/demo.js") }}" type="text/javascript"></script>
<script src="{{ asset("metronic/global/scripts/datatable.js") }}"></script>

<!-- Ключевой скрипт, в который надо вносить изменения -->
<script src="{{ asset("metronic/admin/pages/scripts/ecommerce-products-edit.js") }}"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {    
   Metronic.init(); // init metronic core components
   Layout.init(); // init current layout
   //QuickSidebar.init(); // init quick sidebar
   Demo.init(); // init demo features
   EcommerceProductsEdit.init();
});
</script>

@endsection