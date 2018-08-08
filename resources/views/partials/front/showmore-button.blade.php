<!-- В идеале в кнопке будет максимальный достигнутый id, т.к. мы шарили foreach весь -->
<div class="remove-row mt-4">
  <button id="btn-more-{{ $viewmode }}" class="btn-more btn btn-info btn-lg" data-id="{{ $item->id }}" category-data="{{ $category }}" class="nounderline btn-block mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">Show More</button>
</div>