<?php

Route::get('/', function () {
    return view('welcome');
});

Route::resource('contact', 'ContactController', [
	'except' => ['create']
]);
Route::get('api/contact', 'ContactController@apiContact')->name('api.contact');
Route::get('api/poto', 'ContactController@apiPoto')->name('api.poto');

Route::get('contact/{id}/show', 'ContactController@show')->name('poto');


Route::delete('hapus', 'ContactController@hapus');

