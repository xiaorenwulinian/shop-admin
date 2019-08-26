<?php

namespace App\Model\Kathy;

use Illuminate\Database\Eloquent\Model;
use Silber\Bouncer\Database\Concerns\IsRole;

class MyRole extends Model
{
    use IsRole;
    protected $table = 'roles';
    protected $fillable = [
        'name',
        'title',
        'menu'
    ];

    public function ability()
    {
        return $this->belongsToMany(MyAbility::class, 'permissions', 'entity_id', 'ability_id');
    }
}
