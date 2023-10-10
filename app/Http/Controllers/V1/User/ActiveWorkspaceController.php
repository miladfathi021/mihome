<?php

namespace App\Http\Controllers\V1\User;

use App\Exceptions\UserIsNotPartOfWorkspaceException;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\WorkspaceResource;
use App\Models\Workspace;
use Illuminate\Http\Request;

class ActiveWorkspaceController extends ApiController
{
    /**
     * @param \App\Models\Workspace $workspace
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\UserIsNotPartOfWorkspaceException
     */
    public function store(Workspace $workspace) : \Illuminate\Http\JsonResponse
    {
        if (!auth()->user()->isPartOfWorkspace($workspace)) {
            throw new UserIsNotPartOfWorkspaceException();
        }

        auth()->user()->toggleWorkspace($workspace);

        return $this->responseOk();
    }
}
