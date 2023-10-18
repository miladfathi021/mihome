<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Workspace;
use Database\Seeders\UserWithActiveWorkspaceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WorkspaceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp() : void
    {
        parent::setUp();

        $this->seed(UserWithActiveWorkspaceSeeder::class);
    }

    /** @test **/
    public function owner_can_see_a_list_of_his_workspace()
    {
        $this->withoutExceptionHandling();
        $this->signIn(User::first());

        $this->getJson(route('workspaces.index'))
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'id' => Workspace::first()->id,
                        'name' => Workspace::first()->name
                    ]
                ]
            ]);
    }

    /** @test **/
    public function user_can_switch_between_his_workspaces()
    {
        $this->withoutExceptionHandling();
        $this->signIn(User::first());

        $newWorkspace = Workspace::factory()->create([
            'owner_id' => User::first()->id
        ]);

        auth()->user()->workspaces()->attach($newWorkspace->id);

        $this->postJson(route('workspace.active.store', $newWorkspace->id))
            ->assertStatus(200);

        $this->assertEquals(User::first()->active_workspace_id, $newWorkspace->id);
    }

    /** @test **/
    public function user_can_not_switch_to_other_people_workspaces()
    {
        $workspace = Workspace::factory()->create([
            'owner_id' => User::first()->id
        ]);

        $newUser = User::factory()->create();
        $this->signIn($newUser);


        $this->postJson(route('workspace.active.store', $workspace->id))
            ->assertStatus(400);

        $this->assertNotEquals(User::first()->active_workspace_id, $workspace->id);
    }

    /** @test **/
    public function user_can_create_a_new_workspace()
    {
        $this->withoutExceptionHandling();
        $this->signIn(User::first());

        $data = [
            'name' => 'Second Workspace'
        ];

        $this->assertDatabaseCount('workspaces', 1);

        $this->postJson(route('workspaces.store'), $data)
            ->assertStatus(200);

        $this->assertDatabaseCount('workspaces', 2);
        $this->assertEquals(User::first()->id, Workspace::all()->last()->owner_id);
    }
}
