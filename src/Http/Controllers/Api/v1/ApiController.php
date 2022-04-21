<?php

namespace meysammaghsoudi\todopackage\Http\Controllers\Api\v1;

use meysammaghsoudi\Todopackage\Http\Resources\TaskAPIResource;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use meysammaghsoudi\Todopackage\Models\Task;
use meysammaghsoudi\Todopackage\Models\UserTodo;
use meysammaghsoudi\Todopackage\Models\Label;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use meysammaghsoudi\Todopackage\Http\Resources\LabelAPIResource;

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
                'token' => $user->createToken($request->email)->plainTextToken
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

    public function showTask($id)
    {
        $task = Task::find($id);

        return response()->json([
            'task' => $task
        ], 200);
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

    public function deleteTask($id)
    {
        $task = Task::find($id);
        $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully'
        ], 200);
    }

    public function getTasks(Request $request)
    {
        // return $request->all();
        $user = UserTodo::whereToken(trim($this->getBearer($request)))->first();
        $tasks = $user->tasks;
        return response()->json([
            'tasks' => TaskAPIResource::collection($tasks)
        ]);
    }

    public function createLabelByTask(Request $request, $id)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $task = Task::find($id);
        foreach ($request->name as $label) {
            $task->labels()->create([
                'name' => $label
            ]);
        }

        return response()->json([
            'message' => 'Labels task created successfully'
        ], 201);
    }

    public function getLabelsByTask($id)
    {
        $task = Task::with('labels')->find($id);

        return response()->json([
            'labels' => $task
        ], 200);
    }

    public function getLabels()
    {
        $labels = Label::all();

        return response()->json([
            'labels' => LabelAPIResource::collection($labels)
        ], 200);
    }

    private function getBearer($request)
    {
        $header = $request->header('Authorization', '');
        if (Str::startsWith($header, 'Bearer'))
        {
            return Str::substr($header, 7);
        }
        return '';
    }
}

