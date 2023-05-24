<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\TaskController;
use App\Models\Group;
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

Route::group(['middleware' => ['cors', 'json.response']], function () {

    // Auth
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');

    Route::group(['middleware' => ['auth:api']], function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // User
        Route::get('/user', [UserController::class, 'me'])->name('user.me');
        Route::post('/user', [UserController::class, 'update'])->name('user.update');

        // Group
        Route::get('/group/{group}', [GroupController::class, 'show'])->name('group.show');
        Route::post('/group', [GroupController::class, 'store'])->name('group.store');
        Route::put('/group/{group}', [GroupController::class, 'update'])->can('update', 'group')->name('group.update');
        Route::delete('/group/{group}', [GroupController::class, 'delete'])->can('delete', 'group')->name('group.delete');
        // Route::post('/group/join', [GroupController::class, 'join'])->name('group.join');
        Route::post('/group/invite/{group}', [GroupController::class, 'invite'])->can('invite', 'group')->name('group.invite');

        // Workspace
        Route::get('/workspace/{workspace}', [WorkspaceController::class, 'show'])->name('workspace.show');
        Route::post('/workspace', [WorkspaceController::class, 'store'])->name('workspace.store');
        Route::put('/workspace/{workspace}', [WorkspaceController::class, 'update'])->can('update', 'workspace')->name('workspace.update');
        Route::delete('/workspace/{workspace}', [WorkspaceController::class, 'delete'])->can('update', 'workspace')->name('workspace.delete');
        Route::get('/workspace/{workspace}/boards', [WorkspaceController::class, 'boards'])->name('workspace.boards');
        Route::get('/workspace/{workspace}/members', [WorkspaceController::class, 'members'])->name('workspace.members');

        // Board
        Route::post('/board', [BoardController::class, 'store'])->name('board.store');
        Route::put('/board/{board}', [BoardController::class, 'update'])->name('board.update');
        Route::delete('/board/{board}', [BoardController::class, 'delete'])->name('board.delete');

        // Task
        Route::post('/task', [TaskController::class, 'store'])->name('task.store');
        Route::put('/task/{task}', [TaskController::class, 'update'])->name('task.update');
        Route::delete('/task/{task}', [TaskController::class, 'delete'])->name('task.delete');
    });
});
