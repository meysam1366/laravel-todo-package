<?php

namespace meysammaghsoudi\todopackage\Http\Controllers\Api\v1;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use meysammaghsoudi\Todopackage\Models\Task;
use meysammaghsoudi\Todopackage\Models\UserTodo;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ApiController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users_todo',
            'password' => 'required',
        ]);

        $user = UserTodo::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $user->token = $user->createToken($request->email)->plainTextToken;
        $user->save();

        return response()->json([
            'message' => 'User Created successfully'
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if(Auth::guard('user_todo')->attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = UserTodo::where('email', $request->email)->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            return response()->json([
                'token' => $user->token
            ], 200);
        }else {
            return response()->json([
                'error' => 'User Not Found'
            ], 404);
        }
    }

    public function createTask(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'status' => 'required'
        ]);

        $user = UserTodo::whereToken(trim($this->getBearer($request)))->first();
        $user->tasks()->create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status
        ]);

        return response()->json([
            'message' => 'Task Created successfully'
        ], 201);
    }

    public function updateTask(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required'
        ]);

        $task = Task::find($id);
        $task->update([
            'title' => $request->title,
            'description' => $request->description
        ]);

        return response()->json([
            'message' => 'Task Update successfully'
        ], 200);
    }

    public function changeStatusTask(Request $request, $id)
    {
        $request->validate([
            'status' => 'required'
        ]);

        $task = Task::find($id);
        $task->update([
            'status' => $request->status
        ]);

        return response()->json([
            'message' => 'Task Change Status successfully'
        ], 200);
    }

    public function getBearer($request)
    {
        $header = $request->header('Authorization', '');
        if (Str::startsWith($header, 'Bearer'))
        {
            return Str::substr($header, 7);
        }
        return '';
    }

    public function getTasks()
    {
        return response()->json([
            'tasks' => Task::all()
        ]);
    }
}

