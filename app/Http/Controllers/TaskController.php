<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function createTask()
    {
        return 'Create Task';
    }

    public function getAllTasks()
    {
        return 'Get Tasks';
    }

    public function updateTask($id)
    {
        return 'Create Task with: '.$id;
    }

    public function deleteTask($id)
    {
        return 'Delete Task with: '.$id;
    }
}
