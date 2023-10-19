<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Workspace;
use Database\Seeders\UserWithActiveWorkspaceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp() : void
    {
        parent::setUp();

        $this->seed(UserWithActiveWorkspaceSeeder::class);
    }

    /** @test **/
    public function the_owner_can_invite_a_guest_to_workspace()
    {
        $this->withoutExceptionHandling();
        $this->signIn(User::first());

        $data = [
            'name' => 'john doe',
            'phone' => '09215420796'
        ];

        $this->assertDatabaseCount('users', 1);

        $this->postJson(route('workspace.users.store'), $data)
            ->assertStatus(200);

        $this->assertDatabaseCount('users', 2);
        $this->assertEquals(User::get()->last()->active_workspace_id, Workspace::first()->id);
    }
}
