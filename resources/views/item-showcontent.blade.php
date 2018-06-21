<div class="col-md-12 border border-success">
	<h2>{{ $item->name }}</h2>

	<div class="row">	
		<div class="col-md-5">
			<img src="{{ productImage($item->image) }}" alt="">
		</div>
		<div class="col-md-5">
			<h4>Item options</h4>
		</div>
		<div class="col-md-2">
			<h5>Price</h5>
			{{ $item->viewPrice() }}
		</div>
	</div>

	<div class="row mt-4">
		<div class="col-md-12">
			<p>
				{{ $item->description }}
			</p>
		</div>
	</div>

	<div class="row mt-4">
		<div class="col-md-12">
			@include('components.addtocart-button')
		</div>
	</div>
	
	@if($needSimilarItemsShow)
	<div class="row mt-4">
		<div class="col-md-12">
			<h5>Similar items</h5>
			<div class="card-deck mt-4">
				@foreach($mightAlsoLike as $item)
					@include('item-brief-card', ['category' => ''])
				@endforeach
			</div>
		</div>
	</div>
	@endif
</div>