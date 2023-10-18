<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'password',
        'active_workspace_id',
        'phone_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'phone_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function workspaces() : \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            Workspace::class,
            'user_workspace',
            'user_id',
            'workspace_id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function activeWorkspace() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Workspace::class, 'active_workspace_id');
    }

    /**
     * @param \App\Models\Workspace $workspace
     *
     * @return bool
     */
    public function isPartOfWorkspace(Workspace $workspace) : bool
    {
        return $this->workspaces()
            ->where('user_id', auth()->id())
            ->where('workspace_id', $workspace->id)
            ->exists();
    }

    /**
     * @param \App\Models\Workspace $workspace
     *
     * @return void
     */
    public function toggleWorkspace(Workspace $workspace)
    {
        auth()->user()->update([
            'active_workspace_id' => $workspace->id
        ]);
    }
}
