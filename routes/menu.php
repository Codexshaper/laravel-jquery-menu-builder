<?php

Route::group(['namespace' => 'CodexShaper\Menu\Http\Controllers'], function(){
	Route::get('/admin/menus', 'MenuController@index');
	Route::get('/admin/menus/builder/{id}', 'MenuController@getMenuItems')->name('menus.builder');

	// Ajax

	// Menus
	Route::post('/admin/menu/add', 'MenuController@addMenu');
	Route::get('/admin/menu/edit', 'MenuController@editMenu');
	Route::post('/admin/menu/update', 'MenuController@updateMenu');
	Route::post('/admin/menu/delete', 'MenuController@deleteMenu');
	// Menu Item
	Route::post('/admin/menu/addItem', 'MenuController@addMenuItem');
	Route::post('/admin/menu/sortItem', 'MenuController@sortMenuItem');
	Route::get('/admin/menu/editItem', 'MenuController@editMenuItem');
	Route::post('/admin/menu/editItem', 'MenuController@updateMenuItem');
	Route::post('/admin/menu/deleteItem', 'MenuController@destroyMenuItem');

	Route::get('/admin/assets', 'MenuController@assets')->name('menu.asset');

	// Auth::routes();

	// Route::get('/home', 'HomeController@index')->name('home');
});

