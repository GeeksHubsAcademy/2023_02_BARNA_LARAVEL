<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function createTask(Request $request)
    {
        try {
            Log::info('Creating task');            

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:10',
                'description' => 'required',
            ]);
     
            if ($validator->fails()) {
                return response()->json(
                    [
                        'success' => true,
                        'message' => "Body validation error",
                        'errors' => $validator->errors()
                    ]
                    , 400
                );
            }

            $userId = auth()->user()->id;
            $title = $request->input('title');
            $description = $request->input('description');

            // insert using query builder
            // $newTask = DB::table('tasks')->insert([
            //     'title' => $title,
            //     'description' => $description,
            //     'user_id' => $userId
            // ]);

            //insert with eloquent optionA
            $newTask = new Task();
            $newTask->title = $title;
            $newTask->description = $description;
            $newTask->user_id = $userId;
            $newTask->save();         

            return response()->json(
                [
                    "success" => true,
                    "message" => "Create task successfully",
                    "data" => $newTask
                ],
                201
            );
        } catch (\Throwable $th) {
            Log::alert($th->getMessage());

            return response()->json(
                [
                    "success" => false,
                    "message" => "Error creating task",
                    "error" => $th->getMessage()
                ],
                500
            );
        }
    }

    public function getAllTasks()
    {
        try {
            $userId = auth()->user()->id;

            // GetAllTasksByUser without relations
            // $tasks = Task::query()
            //     ->where('user_id', '=', $userId)
            //     ->get();

            // GetAllTasksByUser with eloquent relations
            $tasks = User::find($userId)->tasks;   ;

            return response()->json(
                [
                    "success" => true,
                    "message" => "Get tasks successfully",
                    "data" => $tasks
                ],
                201
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Error getting tasks",
                    "error" => $th->getMessage()
                ],
                500
            );
        }
    }

    public function updateTask(Request $request, $id)
    {
        try {
            Log::info('Update Task');
            $validator = Validator::make($request->all(), [
                'title' => 'string|max:10',
                'description' => 'string',
                'status' => 'boolean'
            ]);
     
            if ($validator->fails()) {
                return response()->json(
                    [
                        'success' => true,
                        'message' => "Body validation error",
                        'errors' => $validator->errors()
                    ]
                    , 400
                );
            }

            $task = Task::find($id);

            if (!$task) {
                return response()->json(
                    [
                        'success' => true,
                        'message' => "task doesnt exists"
                    ]
                    , 404
                );
            }

            $title = $request->input('title');
            $description = $request->input('description');
            $status = $request->input('status');

            if (isset($title)) {
                $task->title = $request->input('title');
            }

            if (isset($description)) {
                $task->description = $request->input('description');
            }

            if (isset($status)) {
                $task->status = $request->input('status');
            }

            $task->save();

            return response()->json(
                [
                    "success" => true,
                    "message" => "Update task successfully",
                    "data" => $task
                ],
                200
            );


        } catch (\Throwable $th) {
            Log::alert($th->getMessage());

            return response()->json(
                [
                    "success" => false,
                    "message" => "Error updating task",
                    "error" => $th->getMessage()
                ],
                500
            );
        }
    }

    public function deleteTask($id)
    {
        try {
            $userId = auth()->user()->id;

            $task = Task::query()
                ->where('id', '=', $id)
                ->where('user_id', '=', $userId)
                ->first();

            if (!$task) {
                return response()->json(
                    [
                        "success" => true,
                        "message" => "Task doesnt exists",
                    ],
                    404
                );
            }

            $taskDeleted = Task::destroy($id);

            return response()->json(
                [
                    "success" => true,
                    "message" => "Delete task successfully",
                    "data" => $taskDeleted
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Error deleting task",
                    "error" => $th->getMessage()
                ],
                500
            );
        }
    }

    public function addUserToTask(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'users_ids' => 'required',
            ]);
     
            if ($validator->fails()) {
                return response()->json(
                    [
                        'success' => true,
                        'message' => "Body validation error",
                        'errors' => $validator->errors()
                    ]
                    , 400
                );
            }

            $task = Task::query()
                ->where('id', '=', $id)
                ->first();

            if (!$task) {
                return response()->json(
                    [
                        "success" => true,
                        "message" => "Task doesnt exists",
                    ],
                    404
                );
            }

            // comprobar si el usuario existe

            $usersIds = $request->input('users_ids');
            $taskId = $id;

            // todo no permitir mas de 4 usuario en una tarea

            // QUERY BUILDER
            // // $addUserToTask = DB::table('task_user')->insert([
            //     "user_id" => $userId,
            //     "task_id" => $taskId
            // ]);

            // WITH ELOQUENT

            foreach ($usersIds as $userId) {
                $user = User::find($userId);

                $addUserToTask = $user
                ->tasksManyToMany()
                ->attach($taskId);
            }            

            return response()->json(
                [
                    "success" => true,
                    "message" => "Add user to task successfully",
                    "data" => $addUserToTask
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Error adding user to task",
                    "error" => $th->getMessage()
                ],
                500
            );
        }
    }

    public function getTaskUsers($id)
    {
        try {
            $task = Task::find($id);

            return response()->json(
                [
                    "success" => true,
                    "message" => "Tasks users successfully",
                    "data" => $task->usersManyToMany
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Error getting task users",
                    "error" => $th->getMessage()
                ],
                500
            );
        }
    }

    public function deleteUserToTask($taskId, $userId)
    {
        try {
            $user = User::find($userId);

            $user->tasksManyToMany()->detach($taskId);

            return response()->json(
                [
                    "success" => true,
                    "message" => "Tasks user deleted successfully",
                    // "data" => $task->usersManyToMany
                ],
                200
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Error deleting task user",
                    "error" => $th->getMessage()
                ],
                500
            );
        }
    }
}
