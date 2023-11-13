<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workspace extends Model
{
    use HasFactory;

    protected $fillable = ['owner_id', 'name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invitations() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Invitation::class, 'workspace_id');
    }
}
