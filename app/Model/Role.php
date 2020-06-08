<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'role';
    public $timestamps = false;

    public function privilege()
    {
        return $this->belongsToMany(Privilege::class, 'role_privilege', 'role_id', 'pri_id');
    }
}
