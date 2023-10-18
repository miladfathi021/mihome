<?php

namespace App\Services;

class WorkspaceService
{
    /**
     * @param $data
     *
     * @return mixed
     */
    public function create($data) : mixed
    {
        return auth()->user()->workspaces()->create([
            'owner_id' => auth()->id(),
            'name' => $data['name']
        ]);
    }
}
