<?php

namespace App\Http\Controllers\V1\Workspace;

use App\Http\Controllers\ApiController;
use App\Http\Requests\InvitationRequest;

class InvitationController extends ApiController
{
    public function store(InvitationRequest $request)
    {
        auth()->user()->activeWorkspace()->invitations()->create([
            'name' => $request->name,
            'phone' => $request->phone
        ]);
    }
}
