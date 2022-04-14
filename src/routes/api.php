<?php

namespace meysammaghsoudi\Todopackage\routes;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use meysammaghsoudi\Todopackage\Http\Controllers\Api\v1\ApiController;

Route::prefix('api/todo')->group(function() {
    Route::get('/tasks', 'ApiController@getTasks');
});
