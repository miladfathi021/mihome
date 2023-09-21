<?php

namespace App\Services;

use App\Models\User;

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

        $user->workspaces()->create([
            'name' => $data['workspace']
        ]);

        return $user;
    }

    /**
     * @param \App\Models\User $user
     *
     * @return string
     */
    public function createToken(User $user) : string
    {
        return $user->createToken('API Token')->accessToken;
    }
}
