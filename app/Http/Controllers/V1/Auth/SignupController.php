<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\ApiController;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use App\Services\AuthService;

class SignupController extends ApiController
{
    /**
     * @param \App\Http\Requests\SignupRequest $request
     * @param \App\Services\AuthService        $authService
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(SignupRequest $request, AuthService $authService) : \Illuminate\Http\JsonResponse
    {
        $user = $authService->createUser($request->all());

        $token = $authService->createToken($user);

        return $this->responseOk([
            'token' => $token
        ]);
    }
}
