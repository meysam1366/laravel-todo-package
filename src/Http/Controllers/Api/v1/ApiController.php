<?php

namespace meysammaghsoudi\Todopackage\Http\Controllers\Api\v1;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use meysammaghsoudi\Todopackage\Models\Task;

class ApiController extends Controller
{
    public function getTasks()
    {
        return response()->json([
            'tasks' => Task::all()
        ]);
    }
}

