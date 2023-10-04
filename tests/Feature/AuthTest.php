<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function a_person_can_create_a_new_account_with_phone()
    {
        $this->withoutExceptionHandling();
        Artisan::call('passport:install');

        $data = [
            'name' => 'Milad Fathi',
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
    public function the_phone_is_required()
    {
        $data = [
            'name' => 'Milad Fathi',
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
    public function the_phone_must_be_unique()
    {
        User::withoutEvents(function () {
            User::factory()->create([
                'phone' => '09215420796'
            ]);
        });

        $data = [
            'name' => 'Milad Fathi',
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

    /** @test **/
    public function a_workspace_should_be_created_when_the_user_was_created()
    {
        $this->withoutExceptionHandling();
        Artisan::call('passport:install');

        $data = [
            'name' => 'Milad Fathi',
            'phone' => '09215420796',
            'password' => 'password',
            'workspace' => 'parsa'
        ];

        $this->assertDatabaseCount('workspaces', 0);

        $this->postJson(route('signup'), $data)
            ->assertStatus(200);

        $this->assertDatabaseCount('workspaces', 1);
        $this->assertDatabaseHas('workspaces', [
            'name' => $data['workspace'],
            'owner_id' => User::first()->id
        ]);
    }

    /** @test **/
    public function the_workspace_that_is_created_when_a_user_was_created_should_be_active()
    {
        $this->withoutExceptionHandling();
        Artisan::call('passport:install');

        $data = [
            'name' => 'Milad Fathi',
            'phone' => '09215420796',
            'password' => 'password',
            'workspace' => 'parsa'
        ];

        $this->postJson(route('signup'), $data)
            ->assertStatus(200);

        tap(User::all(), function ($users) {
            $this->assertCount(1, $users);
            $this->assertEquals($users[0]->activeWorkspace()->id, Workspace::first()->id);
        });
    }

    /** @test **/
    public function user_can_login_with_email()
    {
        $this->withoutExceptionHandling();
        Artisan::call('passport:install');

        $user = User::factory()->has(Workspace::factory())->create([
            'phone' => '09215420796',
        ]);

        $data = [
            'phone' => '09215420796',
            'password' => 'password'
        ];

        $this->postJson(route('signin'), $data)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'token'
                ],
                'message'
            ]);

        $this->assertEquals(auth()->id(), $user->id);
    }

    /** @test **/
    public function the_phone_is_required_for_login()
    {
        $user = User::factory()->has(Workspace::factory())->create([
            'phone' => '09215420796',
        ]);

        $data = [
            'phone' => '',
            'password' => 'password',
        ];

        $this->postJson(route('signin'), $data)
            ->assertStatus(400);

        $this->assertNotEquals(auth()->id(), $user->id);
    }

    /** @test **/
    public function the_phone_must_be_string_for_login()
    {
        $user = User::factory()->has(Workspace::factory())->create([
            'phone' => '09215420796',
        ]);

        $data = [
            'phone' => 654,
            'password' => 'password',
        ];

        $this->postJson(route('signin'), $data)
            ->assertStatus(400);

        $this->assertNotEquals(auth()->id(), $user->id);
    }

    /** @test **/
    public function the_password_is_required_for_login()
    {
        $user = User::factory()->has(Workspace::factory())->create([
            'phone' => '09215420796',
        ]);

        $data = [
            'phone' => '09215420796',
            'password' => '',
        ];

        $this->postJson(route('signin'), $data)
            ->assertStatus(400);

        $this->assertNotEquals(auth()->id(), $user->id);
    }

    /** @test **/
    public function the_password_must_be_string_for_login()
    {
        $user = User::factory()->has(Workspace::factory())->create([
            'phone' => '09215420796',
        ]);

        $data = [
            'phone' => '09215420796',
            'password' => 1234,
        ];

        $this->postJson(route('signin'), $data)
            ->assertStatus(400);

        $this->assertNotEquals(auth()->id(), $user->id);
    }

    /** @test **/
    public function the_password_must_be_greater_than_5_for_login()
    {
        $user = User::factory()->has(Workspace::factory())->create([
            'phone' => '09215420796',
        ]);

        $data = [
            'phone' => '09215420796',
            'password' => '12345',
        ];

        $this->postJson(route('signin'), $data)
            ->assertStatus(400);

        $this->assertNotEquals(auth()->id(), $user->id);
    }

    /** @test **/
    public function the_password_must_not_be_greater_than_255_for_login()
    {
        $user = User::factory()->has(Workspace::factory())->create([
            'phone' => '09215420796',
        ]);

        $data = [
            'phone' => '09215420796',
            'password' => Str::random(256),
        ];

        $this->postJson(route('signin'), $data)
            ->assertStatus(400);

        $this->assertNotEquals(auth()->id(), $user->id);
    }

    /** @test **/
    public function user_can_not_login_with_wrong_credential()
    {
        $user = User::factory()->has(Workspace::factory())->create([
            'phone' => '09215420796',
        ]);

        $data = [
            'phone' => '09215420796',
            'password' => '123456',
        ];

        $this->postJson(route('signin'), $data)
            ->assertStatus(401)
            ->assertJson([
                'data' => '',
                'message' => 'These credentials do not match our records.'
            ]);

        $this->assertNotEquals(auth()->id(), $user->id);
    }
}
