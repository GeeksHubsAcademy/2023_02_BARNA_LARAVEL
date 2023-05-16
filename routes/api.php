<?php

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

Route::post('/tasks', function () {
    return 'Create Task';
});

Route::get('/tasks', function () {
    return 'Get Tasks';
});

Route::put('/tasks/{id}', function ($id) {
    return 'Update Task with id: '.$id;
});

Route::delete('/tasks/{id}', function ($id) {
    return 'Delete Task with id: '.$id;
});





