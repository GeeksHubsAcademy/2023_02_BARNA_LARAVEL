<?php

use App\Http\Controllers\AuthControler;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserContoller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/', function () {
    return 'Bienvenidos a mi app';
});

// TASKS
Route::group([
    'middleware' => ['auth:sanctum']
], function () {
    Route::post('/tasks', [TaskController::class, 'createTask']);
    Route::get('/tasks', [TaskController::class, 'getAllTasks']);
    Route::put('/tasks/{id}', [TaskController::class, 'updateTask']);
    Route::delete('/tasks/{id}', [TaskController::class, 'deleteTask']);
});

// AUTH
Route::post('/register', [AuthControler::class, 'register']);
Route::post('/login', [AuthControler::class, 'login']);

Route::get('/profile', [AuthControler::class, 'profile'])->middleware('auth:sanctum');


Route::group([
    'middleware' => ['auth:sanctum', 'IsAdmin']
], function () {
    Route::get('/users', [UserContoller::class, 'getAllUsers']);
    Route::post('/add-user-to-task/{id}', [TaskController::class, 'addUserToTask']);
    Route::delete('/delete-user-to-task/{taskId}/{userId}', [TaskController::class, 'deleteUserToTask']);
    Route::get('/get-task-users/{id}', [TaskController::class, 'getTaskUsers']); 
});





