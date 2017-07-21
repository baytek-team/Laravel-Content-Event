<?php

// Load events for the dashboard
Route::get('/dashboard', 'EventController@dashboard');

// Upcoming events
Route::get('/upcoming', 'EventController@upcoming');

// Past Events
Route::get('/past', 'EventController@past');

// Gets a list of all events
Route::get('/', 'EventController@all');

// Get a specific event
Route::get('/{event}', 'EventController@get');

// Get a specific range
Route::get('/range/{start}/to/{end}', 'EventController@range');
