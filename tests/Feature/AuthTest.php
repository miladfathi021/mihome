<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function guest_can_create_a_new_account()
    {
        $this->withoutExceptionHandling();

        $data = [
            'name' => 'Milad Fathi',
            'email' => 'miladfathi021@gmail.com'
        ];
    }
}
