<?php

Route::group(['middleware' => 'auth:api'], function() {
    Route::post('workspaces/{id}/users')
        ->name('workspace.users.store');
});
