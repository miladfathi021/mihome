<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function guest_can_create_a_new_account()
    {
        $this->withoutExceptionHandling();
        Artisan::call('passport:install');

        $data = [
            'name' => 'Milad Fathi',
            'email' => 'miladfathi021@gmail.com',
            'phone' => '',
            'password' => 'password',
            'workspace' => 'parsa'
        ];

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('workspaces', 0);

        $this->postJson(route('signup'), $data)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'token'
                ],
                'message'
            ]);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', [
            'name' => $data['name'],
            'email' => $data['email']
        ]);

        $this->assertDatabaseCount('workspaces', 1);
        $this->assertDatabaseHas('workspaces', [
            'name' => $data['workspace']
        ]);
    }
}
