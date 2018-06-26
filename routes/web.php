<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Гостевой просмотр сайта вне админки
Route::group(['middleware' => 'guest'], function () {
	
		//Отображение домашней страницы и всех товаров + именование роута
		Route::get('/', 'HomeController@index')->name('home.index');
		
		//Листинг категории:
		Route::get('/category/{cat_slug?}', 'HomeController@catIndex')->name('home.catindex');
		
		//Отображение конкретного товара:
		Route::get('/category/{cat_slug?}/{item_slug?}', 'HomeController@show')->name('home.show');
		
		//Подгрузка списков по кнопке Show More
		Route::post('/loadDataAjax', 'HomeController@loadDataAjax');
		
		//Быстрый просмотр индивидуального товара
		Route::post('/quickViewAjax', 'HomeController@quickViewAjax');
		
		//Добавление товара в корзину
		Route::post('/addItemByAJAX', 'CartController@addItemByAJAX');
		
		//Обновление строки заголовка, там надо данные изменить
		Route::post('/refreshCartButton', 'CartController@refreshCartButton');
		
		//Просмотр содержимого корзины товаров
		Route::get('/cart', 'CartController@index');
		Route::get('/cart/delete/{item_hash?}', 'CartController@delete');	
		Route::post('/cart/changeQty', 'CartController@changeQty');
		Route::get('/cart/destroy', 'CartController@destroy');
		Route::post('/cart/applyCoupone', 'CartController@applyCoupone');
		Route::post('/cart/removeCoupone', 'CartController@removeCoupone');
		
		Route::get('/cart/checkout/choicewallet', 'CheckoutController@choiceWallet');
		
        Route::group(['prefix' => 'auth'], function () {
			
			//Так это регистрация юзера или имеющийся логин?
            Route::get('login', 'Auth\LoginController@create')->name('login');
            Route::post('login', 'Auth\LoginController@store');

            //Route::get('forgot', 'Auth\Forgot@create')->name('forgot');
            //Route::post('forgot', 'Auth\Forgot@store');

            //Route::get('reset', 'Auth\Reset@create');
            //Route::get('reset/{token}', 'Auth\Reset@create')->name('reset');
            //Route::post('reset', 'Auth\Reset@store');
        });
    });


//Административное меню
Route::group(['middleware' => 'auth'], function () {
	
	Route::group(['prefix' => 'dashboard'], function () {
            
		Route::get('/', function () {
			return view('dashboard.index');
		});
		
		Route::get('/logout', 'Auth\LoginController@destroy');

		Route::get('/ecommerce', function () {
			return view('dashboard.ecommerce.index');
		});

		Route::get('/orders', function () {
			return view('dashboard.orders.index');
		});

		Route::get('/products', function () {
			return view('dashboard.products.index');
		});

		Route::get('/products/{id?}/edit', function () {
			return view('dashboard.products.index');
		});

		Route::post('/products/{id?}/edit', 'ProductsControllerDash@update');
	});
	
});
