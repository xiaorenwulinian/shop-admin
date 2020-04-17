<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $table = 'attribute';
    public $timestamps = false;

    public $fillable = [
        'attr_name','attr_type','attr_option_values','type_id','is_del'
    ];

    public function attrSaleValue()
    {
       return $this->hasMany('App\Model\AttrSaleValue','attribute_id','id');
    }

}
