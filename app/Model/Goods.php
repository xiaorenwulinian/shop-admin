<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    protected $table = 'goods';
    public $timestamps = false;

    public $fillable = [
        'id','goods_name','goods_number','category_id','brand_id',
        'market_price','shop_price','jifen','jifen_price','promote_price','promote_start_time',
        'promote_end_time','goods_img','goods_thumb_img','is_hot','is_new','is_best',
        'is_on_sale','seo_keyword','seo_description','type_id','sort_num','addtime','is_delete'
    ];

}
