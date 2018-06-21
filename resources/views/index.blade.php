@extends('layouts.front')

@section('title', 'Hello, customer!')

@section('content')
<div class="col-md-12">

<!-- Переключатель режимов списка -->
<div class="row justify-content-md-center" id="screen-modes">
    <script type="text/javascript">
      
      //Возвращаем сохранённый вид разметки в локальном хранилище браузера
      let savedLayout = localStorage.getItem("layout");
      layout = savedLayout ? savedLayout : "card";

      function setList() { 
        layout = "list";
        localStorage.setItem('layout', 'list');

        $(document).ready(function() {
         $("#cardview-mode").css('display', 'none');
         $("#listview-mode").css('display', 'block');
       });
      }

      function setTile() { 
        layout = "card";
        localStorage.setItem('layout', 'card');

        $(document).ready(function() {
         $("#listview-mode").css('display', 'none');
         $("#cardview-mode").css('display', 'flex');
       });
      }
    </script>
  
    <!-- Надо это дело растянуть по широте -->
    <div class="col">Screen Mode:</div>
    <div class="col" style="cursor: pointer;">
      <span class="fa fa-list" onclick="setList()"></span>
      <span class="fa fa-folder" onclick="setTile();"></span>
    </div>
</div>

<!-- Основной контент страницы -->
<div class="row">
  <!-- Переключать на категорию может? -->
  @if($category)
    <h2>{{ $category->name }}</h2>
  @else
    <h2>All virtual products</h2>
  @endif

<!-- Задача переключаться между видами -->
<div class="list-group load-data" id="listview-mode" style="display: none;">
  @forelse ($items as $item)
    @include('item-brief-listitem')
  @empty
    <div>No items found</div>
  @endforelse
  
  <!-- В $item будет хранится последний элемент итерации -->
  @include('components.showmore-button', ['viewmode' => 'listview', 'item' => $item, 'category' => $category])
</div>

<div class="card-deck mt-4 load-data" id="cardview-mode" style="display: none;">
      @forelse ($items as $item)
        @include('item-brief-card')
      @empty
          <div>No items found</div>
      @endforelse
      
      <!-- В $item будет хранится последний элемент итерации -->
      @include('components.showmore-button', ['viewmode' => 'cardview', 'item' => $item, 'category' => $category])
</div><!-- end class=card-deck -->

<!-- А нельзя ли замену классов в блоке делать при нажатии кнопки переключения режима, а? -->
<!-- И по blade-if разные шаблоны включать -->

</div><!-- end div class row -->

<!-- Модальное окно Быстрый Просмотр товара -->
<!-- Modal -->
<div class="modal fade" id="quickview-window" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 70%;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Quick View of Goods</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <!-- Контент из AJAX-подгрузки будет! -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<script>window.__theme = 'bs4';</script>
<script>

$ = jQuery;

$(document).ready(function(){

  if (layout == 'list') {
  $("#cardview-mode").css('display', 'none');
  $("#listview-mode").css('display', 'block');
} else {
  $("#listview-mode").css('display', 'none');
  $("#cardview-mode").css('display', 'flex');
}
  
  //Здесь очищаем контент Quick View при закрытии
  $('#quickview-window').on('hidden.bs.modal', function (e) {
      $('.modal-body').empty();
  });

   $(document).on('click','.btn-quickview', function(event) {
      var item_id = $(this).attr('item-id');
      event.preventDefault();

      $.ajax({
           url : '{{ url("quickViewAjax") }}',
           method : "POST",
           data : {item_id: item_id, _token:"{{ csrf_token() }}" },
           dataType : "text",
           success : function(data)
           {
              //Тип данных = string, который на деле object
              if(data != '') 
              {
                  $('#quickview-window').modal('show');
                  $('.modal-body').append(data);
              }
              else
              {
                  //$('#btn-more').html("No Data");
              }
           },
           error: function(errmsg) {
              console.log(errmsg.responseText)
           }
       });
   });

   $(document).on('click','.btn-more',function() {

       var id = $(this).data('id');

       //Категория в которой щелкается кнопка Show More
       var category = $(this).attr('category-data');
      
       $(".btn-more").html("Loading....");
       $.ajax({
           url : '{{ url("loadDataAjax") }}',
           method : "POST",
           data : {id: id, _token:"{{ csrf_token() }}", category: category },
           dataType : "text",
           success : function(data)
           {
              if(data != '') 
              {
                  $('.remove-row').remove();

                  //Тип данных = string, который на деле object
                  //console.log(data);
                  objData = JSON.parse(data);

                  $('#cardview-mode').append(objData.cardView);
                  $('#listview-mode').append(objData.listView);
              }
              else
              {
                  $('#btn-more').html("No Data");
              }
           },
           error: function(errmsg) {
              console.log(errmsg.responseText)
           }
       });
   });  
}); 
</script>
</div>
@endsection