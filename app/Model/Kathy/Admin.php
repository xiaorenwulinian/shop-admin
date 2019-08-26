<?php

namespace App\Model\Kathy;

use Illuminate\Database\Eloquent\Model;
use Silber\Bouncer\Database\HasRolesAndAbilities;

class Admin extends Model
{
    use HasRolesAndAbilities;

    protected $fillable = [
        'username',
        'phone',
        'password',
        'avatar',
        'last_login',
        'status'
    ];

    public function role()
    {
        return $this->belongsToMany(MyRole::class, 'assigned_roles', 'entity_id', 'role_id');
    }

}
