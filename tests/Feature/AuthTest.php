<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function the_guest_can_create_a_new_account_with_email()
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
            'email' => $data['email'],
            'phone' => null
        ]);

        $this->assertDatabaseCount('workspaces', 1);
        $this->assertDatabaseHas('workspaces', [
            'name' => $data['workspace']
        ]);
    }

    /** @test **/
    public function the_guest_can_create_a_new_account_with_phone()
    {
        $this->withoutExceptionHandling();
        Artisan::call('passport:install');

        $data = [
            'name' => 'Milad Fathi',
            'email' => '',
            'phone' => '09215420796',
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
            'phone' => $data['phone'],
            'email' => null
        ]);

        $this->assertDatabaseCount('workspaces', 1);
        $this->assertDatabaseHas('workspaces', [
            'name' => $data['workspace']
        ]);
    }

    /** @test **/
    public function the_name_is_required()
    {
        $data = [
            'name' => '',
            'email' => '',
            'phone' => '09215420796',
            'password' => 'password',
            'workspace' => 'parsa'
        ];

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('workspaces', 0);

        $this->postJson(route('signup'), $data)
            ->assertStatus(400);

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('workspaces', 0);
    }

    /** @test **/
    public function the_name_must_be_string()
    {
        $data = [
            'name' => 234,
            'email' => '',
            'phone' => '09215420796',
            'password' => 'password',
            'workspace' => 'parsa'
        ];

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('workspaces', 0);

        $this->postJson(route('signup'), $data)
            ->assertStatus(400);

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('workspaces', 0);
    }

    /** @test **/
    public function the_name_must_be_greater_than_2_chars()
    {
        $data = [
            'name' => 'mi',
            'email' => '',
            'phone' => '09215420796',
            'password' => 'password',
            'workspace' => 'parsa'
        ];

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('workspaces', 0);

        $this->postJson(route('signup'), $data)
            ->assertStatus(400);

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('workspaces', 0);
    }

    /** @test **/
    public function the_name_must_not_be_greater_than_255_chars()
    {
        $data = [
            'name' => Str::random(256),
            'email' => '',
            'phone' => '09215420796',
            'password' => 'password',
            'workspace' => 'parsa'
        ];

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('workspaces', 0);

        $this->postJson(route('signup'), $data)
            ->assertStatus(400);

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('workspaces', 0);
    }

    /** @test **/
    public function the_email_is_required_when_the_phone_is_empty()
    {
        $data = [
            'name' => 'Milad Fathi',
            'email' => '',
            'phone' => '',
            'password' => 'password',
            'workspace' => 'parsa'
        ];

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('workspaces', 0);

        $this->postJson(route('signup'), $data)
            ->assertStatus(400);

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('workspaces', 0);
    }

    /** @test **/
    public function the_email_must_be_a_valid_email_address()
    {
        $data = [
            'name' => 'Milad Fathi',
            'email' => 'milad',
            'phone' => '',
            'password' => 'password',
            'workspace' => 'parsa'
        ];

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('workspaces', 0);

        $this->postJson(route('signup'), $data)
            ->assertStatus(400);

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('workspaces', 0);
    }

    /** @test **/
    public function the_email_must_be_unique()
    {
        User::withoutEvents(function () {
            User::factory()->create([
                'email' => 'miladfathi021@gmail.com'
            ]);
        });

        $data = [
            'name' => 'Milad Fathi',
            'email' => 'miladfathi021@gmail.com',
            'phone' => '',
            'password' => 'password',
            'workspace' => 'parsa'
        ];

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('workspaces', 0);

        $this->postJson(route('signup'), $data)
            ->assertStatus(400);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('workspaces', 0);
    }

    /** @test **/
    public function the_phone_is_required_when_the_email_is_empty()
    {
        $data = [
            'name' => 'Milad Fathi',
            'email' => '',
            'phone' => '',
            'password' => 'password',
            'workspace' => 'parsa'
        ];

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('workspaces', 0);

        $this->postJson(route('signup'), $data)
            ->assertStatus(400);

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('workspaces', 0);
    }

    /** @test **/
    public function the_email_must_be_string()
    {
        $data = [
            'name' => 'Milad Fathi',
            'email' => 'miladfathi021@gmail.com',
            'phone' => 8867,
            'password' => 'password',
            'workspace' => 'parsa'
        ];

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('workspaces', 0);

        $this->postJson(route('signup'), $data)
            ->assertStatus(400);

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('workspaces', 0);
    }

    /** @test **/
    public function the_phone_must_be_unique()
    {
        User::withoutEvents(function () {
            User::factory()->create([
                'email' => '',
                'phone' => '09215420796'
            ]);
        });

        $data = [
            'name' => 'Milad Fathi',
            'email' => '',
            'phone' => '09215420796',
            'password' => 'password',
            'workspace' => 'parsa'
        ];

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('workspaces', 0);

        $this->postJson(route('signup'), $data)
            ->assertStatus(400);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('workspaces', 0);
    }

    /** @test **/
    public function the_password_is_required()
    {
        $data = [
            'name' => 'Milad Fathi',
            'email' => '',
            'phone' => '09215420796',
            'password' => '',
            'workspace' => 'parsa'
        ];

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('workspaces', 0);

        $this->postJson(route('signup'), $data)
            ->assertStatus(400);

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('workspaces', 0);
    }

    /** @test **/
    public function the_password_must_be_string()
    {
        $data = [
            'name' => 'Milad Fathi',
            'email' => '',
            'phone' => '09215420796',
            'password' => 1233465,
            'workspace' => 'parsa'
        ];

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('workspaces', 0);

        $this->postJson(route('signup'), $data)
            ->assertStatus(400);

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('workspaces', 0);
    }

    /** @test **/
    public function the_password_must_be_greater_than_5_chars()
    {
        $data = [
            'name' => 'Milad Fayhi',
            'email' => '',
            'phone' => '09215420796',
            'password' => 'pa',
            'workspace' => 'parsa'
        ];

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('workspaces', 0);

        $this->postJson(route('signup'), $data)
            ->assertStatus(400);

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('workspaces', 0);
    }

    /** @test **/
    public function the_password_must_not_be_greater_than_255_chars()
    {
        $data = [
            'name' => Str::random(256),
            'email' => '',
            'phone' => '09215420796',
            'password' => Str::random(256),
            'workspace' => 'parsa'
        ];

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('workspaces', 0);

        $this->postJson(route('signup'), $data)
            ->assertStatus(400);

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('workspaces', 0);
    }

    /** @test **/
    public function the_workspace_is_required()
    {
        $data = [
            'name' => 'Milad Fathi',
            'email' => '',
            'phone' => '09215420796',
            'password' => 'password',
            'workspace' => ''
        ];

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('workspaces', 0);

        $this->postJson(route('signup'), $data)
            ->assertStatus(400);

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('workspaces', 0);
    }

    /** @test **/
    public function the_workspace_must_be_string()
    {
        $data = [
            'name' => 'Milad Fathi',
            'email' => '',
            'phone' => '09215420796',
            'password' => 'password',
            'workspace' => 1222
        ];

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('workspaces', 0);

        $this->postJson(route('signup'), $data)
            ->assertStatus(400);

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('workspaces', 0);
    }

    /** @test **/
    public function the_workspace_must_be_greater_than_2_chars()
    {
        $data = [
            'name' => 'Milad Fayhi',
            'email' => '',
            'phone' => '09215420796',
            'password' => 'password',
            'workspace' => 'pa'
        ];

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('workspaces', 0);

        $this->postJson(route('signup'), $data)
            ->assertStatus(400);

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('workspaces', 0);
    }

    /** @test **/
    public function the_workspace_must_not_be_greater_than_255_chars()
    {
        $data = [
            'name' => Str::random(256),
            'email' => '',
            'phone' => '09215420796',
            'password' => 'password',
            'workspace' => Str::random(256)
        ];

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('workspaces', 0);

        $this->postJson(route('signup'), $data)
            ->assertStatus(400);

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('workspaces', 0);
    }
}
