<?php

namespace App;

use Illuminate\Database\Eloquent\Model as BaseModel;
class Model extends BaseModel
{
    protected $guarded = []; //黑名单
//    protected $fillable = ['title', 'content'];//开启白名单字段
}
