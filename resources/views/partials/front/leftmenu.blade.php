<h2>Categories</h2>

<ul class="nav flex-column">

 <li>
	<a href="/">All items</a>
 </li>

 @php
 	$categories = \App\Models\Category::all(); //Для левого меню
 @endphp

 @foreach ($categories as $category)
	<li class="{{ setActiveCategory($category->slug) }}">
		<a href="{!! action('HomeController@catIndex', $category->slug) !!}">{!! $category->name !!} </a>
	</li>
 @endforeach 
  
</ul>