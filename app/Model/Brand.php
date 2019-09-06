<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table = 'brand';
    public $timestamps = false;

    public $fillable = [
        'brand_name','site_url','brand_img','is_del'
    ];

}
