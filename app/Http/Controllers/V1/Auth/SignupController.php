<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\ApiController;
use App\Http\Requests\SignupRequest;
use App\Models\User;

class SignupController extends ApiController
{
    /**
     * @param \App\Http\Requests\SignupRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(SignupRequest $request) : \Illuminate\Http\JsonResponse
    {
        $data = $request->all();
        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);
        // After user created the WorkspaceObserver makes a new workspace

        // Create token for user
        $token = $user->createToken('API Token')->accessToken;

        return $this->responseOk([
            'token' => $token
        ]);
    }
}
