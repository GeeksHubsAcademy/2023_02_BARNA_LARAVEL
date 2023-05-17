<?php

namespace App\Http\Controllers;

use App\Models\Task;
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
                'user_id' => 'required'
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

            $title = $request->input('title');
            $description = $request->input('description');
            $userId = $request->input('user_id');

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

    public function getAllTasks($id)
    {
        try {
            $tasks = Task::query()
                ->where('user_id', '=', $id)
                ->get();

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

    public function updateTask($id)
    {
        return 'Create Task with: ' . $id;
    }

    public function deleteTask($id)
    {
        try {
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
}
