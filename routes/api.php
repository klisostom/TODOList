<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;

Route::group([], function () {
    Route::post('/tarefas', [TodoController::class, 'store']);
    Route::put('/tarefas/{todoId}', [TodoController::class, 'update']);
    Route::delete('/tarefas/{todoId}', [TodoController::class, 'destroy']);
    Route::get('/tarefas/{todoId}', [TodoController::class, 'show']);
    Route::get('/tarefas', [TodoController::class, 'index']);
});
