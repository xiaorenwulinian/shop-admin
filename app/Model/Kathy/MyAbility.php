<?php

namespace App\Model\Kathy;

use Illuminate\Database\Eloquent\Model;

class MyAbility extends Model
{
    protected $table = 'abilities';

    protected $fillable = [
        'name',
        'parent_id',
        'desc',
    ];

    public function child()
    {
        return $this->hasMany(MyAbility::class, 'parent_id')->select(['id', 'name', 'desc', 'parent_id']);
    }
}
