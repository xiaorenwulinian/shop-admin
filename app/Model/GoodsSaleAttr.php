<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class GoodsSaleAttr extends Model
{
    protected $table = 'attr_sale_value';
    public $timestamps = false;

    public $fillable = [
        'goods_id','attr_id','attr_sale_id','attr_sale_values','type_id'
    ];




}
