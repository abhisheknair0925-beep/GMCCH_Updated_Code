<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/queue/{unit_id}', 'App\Http\Controllers\QueueController@index')->name('queue.index');
Route::post('/queue/call-next', 'App\Http\Controllers\QueueController@callNext')->name('queue.callNext');

Route::get('/display/{unit_id}', 'App\Http\Controllers\DisplayController@index')->name('display.index');

Route::post('/logout', function () {
    auth()->logout();
    return redirect('/');
})->name('logout');
