<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Passport;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function signIn($user = null)
    {
        $this->flushSession();

        $user = $user ?? User::factory()->create();

        Auth::setUser($user);
        Passport::actingAs($user);

        return $user;
    }
}
