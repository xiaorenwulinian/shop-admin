<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class GoodsCategory extends Model
{
    protected $table = 'goods_category';
    public $timestamps = false;

    public $fillable = [
        'category_name','parent_id'
    ];

}
