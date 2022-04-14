<?php

namespace meysammaghsoudi\Todopackage\routes;

use Illuminate\Support\Facades\Route;

Route::prefix('api/todo')->namespace('meysammaghsoudi\Todopackage\Http\Controllers\Api\v1')->group(function() {
    Route::get('/tasks', 'ApiController@getTasks');
    
});
