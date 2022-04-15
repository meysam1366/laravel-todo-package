<?php

namespace meysammaghsoudi\Todopackage\routes;

use Illuminate\Support\Facades\Route;

Route::prefix('api/todo')->namespace('meysammaghsoudi\Todopackage\Http\Controllers\Api\v1')->group(function() {
    Route::post('/register', 'ApiController@register');
    Route::post('/login', 'ApiController@login');
    Route::post('/task/create', 'ApiController@createTask');
    Route::post('/task/edit/{id}', 'ApiController@editTask');
    Route::patch('/task/{id}/update', 'ApiController@updateTask');
    Route::patch('/task/{id}/change-status', 'ApiController@changeStatusTask');
    Route::delete('/task/{id}/delete', 'ApiController@deleteTask');
    Route::get('/tasks', 'ApiController@getTasks');
});
