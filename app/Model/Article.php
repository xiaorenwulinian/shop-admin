<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Article extends Model
{
    protected $table = 'article';
    public $timestamps = false;
    protected $fillable = [
        'art_title','art_desc','art_content','type',
        'order_id','add_time','edit_time'
    ];



}
