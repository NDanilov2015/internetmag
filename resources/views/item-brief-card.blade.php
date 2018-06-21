@php
$category = $item->categories()->first();
$itemPath = $category->slug . '/' . $item->slug;
@endphp
<div class="col-md-4 mb-4">
	<div class="card">
		<a href="{!! action('HomeController@show', $itemPath) !!}">
			<img class="card-img-top" src="{{ productImage($item->image) }}" style='width: 200px;' alt="Card image cap">
		</a>
		<div class="card-body">
			<div class="row">
		  		<div class="col-md-6">
		  			<h5 class="card-title"><a href="{!! action('HomeController@show', $itemPath) !!}">{{ $item->name }}</a></h5>
		  		</div>
		  		<div class="col-md-6">
		  			<h5>{{ $item->viewPrice() }}</h5>
		  		</div>
		  	</div>
		  	<div class="row">
		  <p class="card-text">{{ $item->description }}</p>
		  @include('components.addtocart-button')
		  &nbsp;
		  <a href="#" class="btn btn-success btn-quickview" item-id="{{ $item->id }}">Quick View</a>
		  </div>
		</div>
	</div>
</div>