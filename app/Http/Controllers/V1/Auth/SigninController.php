<?php

namespace App\Http\Controllers\V1\Auth;

use App\Http\Controllers\ApiController;
use App\Http\Requests\SigninRequest;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;

class SigninController extends ApiController
{
    /**
     * @param \App\Http\Requests\SigninRequest $request
     * @param \App\Services\AuthService        $authService
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(SigninRequest $request, AuthService $authService) : \Illuminate\Http\JsonResponse
    {
        if (Auth::attempt($request->all())) {
            $token = $authService->createToken(\auth()->user());

            return $this->responseOk([
                'token' => $token
            ]);
        }

        return $this->setStatus(401)->response(message: 'These credentials do not match our records.');
    }
}
