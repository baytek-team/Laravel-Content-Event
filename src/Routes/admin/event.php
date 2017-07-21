<?php

Route::group(['as' => 'events.'], function () {
    Route::resource('category', CategoryController::class);
});

Route::get('event/upcoming', 'EventController@upcoming')->name('event.upcoming');
Route::get('event/past', 'EventController@past')->name('event.past');
Route::get('event/featured', 'EventController@featured')->name('event.featured');
Route::resource('event', EventController::class);
