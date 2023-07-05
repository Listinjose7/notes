<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotesController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::post('/login', [AuthController::class, 'login']);
Route::post('/signup', [AuthController::class, 'signup']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/notes/create', [NotesController::class, 'create']);
    Route::put('/notes/update/{noteId}', [NotesController::class, 'update']);
    Route::delete('/notes/delete/{noteId}', [NotesController::class, 'delete']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
Route::get('/user/{userId}', [UserController::class, 'getUser']);

Route::get('/', function () {
    return view('welcome');
});

