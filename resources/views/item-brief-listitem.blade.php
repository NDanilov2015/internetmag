@php
$category = $item->categories()->first();
$itemPath = $category->slug . '/' . $item->slug;
@endphp
<div class="row border border-top" item-id="{{ $item->id }}">
	<div class="col-md-2">
		<a href="{!! action('HomeController@show', $itemPath) !!}">
			<img src="{{ productImage($item->image) }}" style='width: 100px;' alt="">
		</a>
	</div>
	<div class="col-md-6">
		<h5><a href="{!! action('HomeController@show', $itemPath) !!}">{{ $item->name }}</a></h5>
		<p>
			{{ $item->description }}
		</p>
	</div>
	<div class="col-md-4">
		<form class="form-horizontal">
			<h3>{{ $item->viewPrice() }}</h3>
			<!--
			<label class="checkbox">
				<input type="checkbox">Adds product to compair
			</label><br>
		-->
			<div class="btn-group">
				@include('components.addtocart-button')
				&nbsp;
				<a href="#" class="btn btn-success btn-quickview" item-id="{{ $item->id }}">Quick View</a>
			</div>
		</form>
	</div>
</div>