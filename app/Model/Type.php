<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $table = 'type';
    public $timestamps = false;

    public $fillable = [
        'type_name','is_del'
    ];

}
