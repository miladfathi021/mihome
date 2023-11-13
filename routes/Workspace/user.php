<?php

use App\Http\Controllers\V1\Workspace\InvitationController;

Route::group(['middleware' => 'auth:api'], function() {
    Route::post('workspaces/invitations', [InvitationController::class, 'store'])
        ->name('workspace.invitations.store');
});
