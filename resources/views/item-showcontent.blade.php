<div class="col-md-12 border border-success">
	<h2>{{ $item->name }}</h2>

	<div class="row">	
		<div class="col-md-5">
			<img src="{{ productImage($item->image) }}" alt="">
		</div>
		<div class="col-md-5">
			<h4>Item options</h4>
			<form>
			    <fieldset id="imode">
					<h5>Choose instance mode</h5>
			        <input id="imode-normal" type="radio" value="normal" name="imode" checked><label for="imode-normal">Normal</label><br/>
			        <input id="imode-heroic" type="radio" value="heroic" name="imode"><label for="imode-heroic">Heroic</label><br/>
					<input id="imode-mythic" type="radio" value="mythic" name="imode"><label for="imode-mythic">Mythic</label><br/>
			    </fieldset>

			    <fieldset id="loot">
					<h5>Choose loot</h5>
			        <input id="personal" type="radio" value="personal" name="loot" checked><label for="personal">Personal loot</label><br/>
			        <input id="master" type="radio" value="master" name="loot"><label for="master">Master loot</label><br/>
			        <input id="fullgear" type="radio" value="fullgear" name="loot"><label for="fullgear">Full gear</label><br/>
					<input id="bisgear" type="radio" value="bisgear" name="loot"><label for="bisgear">Bis gear</label><br/>
			    </fieldset>
			</form>
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
			@include('partials.front.addtocart-button')
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