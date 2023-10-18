<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\WorkspaceRequest;
use App\Http\Resources\WorkspaceCollection;
use App\Services\WorkspaceService;
use Illuminate\Http\Request;

class WorkspaceController extends ApiController
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index() : \Illuminate\Http\JsonResponse
    {
        $workspaces = auth()->user()->workspaces;

        return $this->responseOk(
            new WorkspaceCollection($workspaces)
        );
    }

    /**
     * @param \App\Http\Requests\WorkspaceRequest $request
     * @param \App\Services\WorkspaceService      $workspaceService
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(WorkspaceRequest $request, WorkspaceService $workspaceService) : \Illuminate\Http\JsonResponse
    {
        $workspaceService->create($request->all());

        return $this->responseOk();
    }
}
