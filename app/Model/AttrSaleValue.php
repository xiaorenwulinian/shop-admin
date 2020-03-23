<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AttrSaleValue extends Model
{
    protected $table = 'attr_sale_value';
    public $timestamps = false;

    public $fillable = [
        'attribute_id','attr_name','attr_name_values','type_id'
    ];

    public function attribute()
    {
        return $this->belongsTo('App\Model\Attribute','attribute_id');
    }



}
