<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', 'App\Http\Controllers\DashboardController@index')->name('dashboard');
Route::get('/bookings', 'App\Http\Controllers\WebBookingController@index')->name('bookings.index');
Route::get('/booking/create', 'App\Http\Controllers\WebBookingController@create')->name('bookings.create');
Route::post('/booking/store', 'App\Http\Controllers\WebBookingController@store')->name('bookings.store');
Route::post('/booking/{id}/cancel', 'App\Http\Controllers\WebBookingController@cancel')->name('bookings.cancel');

Route::get('/queue/{unit_id}', 'App\Http\Controllers\QueueController@index')->name('queue.index');
Route::post('/queue/call-next', 'App\Http\Controllers\QueueController@callNext')->name('queue.callNext');

Route::get('/display/{unit_id}', 'App\Http\Controllers\DisplayController@index')->name('display.index');

Route::post('/logout', function () {
    auth()->logout();
    return redirect('/');
})->name('logout');
