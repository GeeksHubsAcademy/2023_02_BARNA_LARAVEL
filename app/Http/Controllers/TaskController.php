<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function createTask(Request $request)
    {
        try {
            $title = $request->input('title');
            $description = $request->input('description');
            $userId = $request->input('user_id');

            // TODO Validaciones

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
        return 'Get Tasks';
    }

    public function updateTask($id)
    {
        return 'Create Task with: ' . $id;
    }

    public function deleteTask($id)
    {
        return 'Delete Task with: ' . $id;
    }
}
