<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    /**
     * @param array $data
     *
     * @return \App\Models\User
     */
    public function createUser(array $data): User
    {
        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

        $workspace = $user->workspaces()->create([
            'name' => $data['workspace'],
            'owner_id' => $user->id
        ]);

        $user->update([
            'active_workspace_id' => $workspace->id
        ]);

        return $user;
    }

    /**
     * @param $user
     *
     * @return string
     */
    public function login($user) : string
    {
        Auth::login($user);
        return $this->createToken($user);
    }

    /**
     * @param \App\Models\User $user
     *
     * @return string
     */
    public function createToken(User|Authenticatable $user) : string
    {
        return $user->createToken('API Token')->accessToken;
    }
}
