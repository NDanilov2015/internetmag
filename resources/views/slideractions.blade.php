@php
	//$weeks_types = ['firstweek', 'secondweek', 'thirdweek', 'fourthweek'];
	
	$today = getdate()['mday'];
	
	if (($today >= 1) && ($today <= 7)) {
		$current_week = 'firstweek';
	}	
	
	if (($today >= 8) && ($today <= 14)) {
		$current_week = 'secondweek';
	}
	
	if (($today >= 15) && ($today <= 21)) {
		$current_week = 'thirdweek';
	}
	
	//Very long "4st" week
	if (($today >= 22) && ($today <= 31)) {
		$current_week = 'fourthweek';
	}
	
	$promotion_items_data = \App\Models\ItemAction::where($current_week, "1")->get();
	$promotions_items = [];
	
	//Теперь надо вернуть $promotion_items из таблицы items по item_id вообще-то. Есть ли единый запрос?
	//Да нам надо ввести наверное has() какой-нибудь. Или иное отношение между таблицами
	foreach ($promotion_items_data as $promo_data) {
		$promotion_items[] = \App\Models\Item::find($promo_data->item_id);
	}
	
	//Пока более не редактируй этот код - работает и слава Богу!
		
@endphp

<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
  <ol class="carousel-indicators">
	<li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
    
	  @for($i = 1; $i < count($promotion_items); $i++)
		<li data-target="#carouselExampleIndicators" data-slide-to="{{ $i }}"></li>
	  @endfor
  </ol>
  <div class="carousel-inner">
  @foreach($promotion_items as $item)
	<div class='carousel-item bg bg-dark pl-2 pt-2 pb-2 border border-0 {{ ($loop->iteration == 1) ? "active":"" }}'>
			<div style="display: block !important;">
				<a href=""><img src="{{ productImage($item->image) }}" style="width: 320px !important; height: 178px;" />
			</div>
		
			<div class="carousel-caption right-caption text text-white" style="display: block; margin-left: 50%;">
				<h5><a href="" class="text text-white">{{ $item->name }}</a></h5>
				{{ substr($item->description, 0, 60) }}
			</div>
    </div>
  @endforeach
  </div><!-- end class "carousel-inner" -->
  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>