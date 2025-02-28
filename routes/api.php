<?php

Use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TasksController;

use App\Http\Controllers\AuthController;

Route::prefix('v1')->group(function () {
    Route::group([
        'middleware' => 'api',
        'prefix' => 'auth'
    ], function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
        Route::get('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me', [AuthController::class, 'me'])->middleware('auth:api');
    });

    Route::middleware('jwt.guard:api')->group(function () {
        Route::prefix('users')->group(function () {
            Route::get('', [UserController::class, 'show']);
            Route::put('{id}', [UserController::class, 'update']);
            Route::delete('{id}', [UserController::class, 'destroy']);
        });

        // Gerenciamento de projetos
        Route::prefix('projects')->group(function () {
            Route::get('', [ProjectController::class, 'index']);
            Route::post('', [ProjectController::class, 'store']);
            Route::get('{id}', [ProjectController::class, 'show']);
            Route::put('{id}', [ProjectController::class, 'update']);
            Route::delete('{id}', [ProjectController::class, 'destroy']);

            // Gerenciamento de tarefas dentro dos projetos
            Route::prefix('{projectId}/tasks')->group(function () {
                Route::get('', [TasksController::class, 'index']);
                Route::post('', [TasksController::class, 'store']);
                Route::get('{taskId}', [TasksController::class, 'show']);
                Route::put('{taskId}', [TasksController::class, 'update']);
                Route::delete('{taskId}', [TasksController::class, 'destroy']);
            });
        });
    });
});
