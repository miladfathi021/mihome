<?php

use App\Http\Controllers\V1\User\ActiveWorkspaceController;
use App\Http\Controllers\V1\User\WorkspaceController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('/workspaces', [WorkspaceController::class, 'index'])
        ->name('workspaces.index');

    Route::post('/workspaces', [WorkspaceController::class, 'store'])
        ->name('workspaces.store');

    Route::post('/workspaces/{workspace}/active', [ActiveWorkspaceController::class, 'store'])
        ->name('workspace.active.store');
});
