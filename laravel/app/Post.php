<?php

namespace App;

use App\Model;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

//Post 对应 posts 表
//假如不对应的话 需要专门指定 ps:protected $tsble = "post2";
class Post extends Model
{
    use Searchable;
    //定义索引里面的type
    public function searchableAs()
    {
        return 'post';
    }
    //定义有哪些字段需要被搜索
    public function toSearchableArray()
    {
        return [
            'title'=>$this->title,
            'content'=>$this->content,
        ];
    }


    //模型关联
    public function user(){
        return $this->belongsTo('App\User');
    }

    //评论模型
    public function comments()
    {
        return $this->hasMany('App\Comment')->orderBy('created_at','desc');
    }

    //和用户进行关联 ，一个用户一个赞C:\Program Files\Java\jdk1.8.0_181\
    public function zan($user_id)
    {
        return $this->hasOne(\App\Zan::class)->where('user_id',$user_id);
    }
    //一篇文章总共的赞
    public function zans()
    {
        return $this->hasMany(\App\Zan::class);
    }
    //属于某个作者的文章
    public function scopeAuthorBy(Builder $query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }
    //
    public function postTopics()
    {
        return $this->hasMany(\App\PostTopic::class,'post_id','id');
    }
    //不属于某个作者的文章
    public function scopeTopicNotBy(Builder $query, $topic_id)
    {
        return $query->doesntHave('postTopics','and',function ($q) use($topic_id ){
            $q->where('topic_id',$topic_id);
        });
    }
    //全局scope的方式
    protected static function boot()
    {
        parent::boot();
        static ::addGlobalScope('avaiable',function (Builder $builder){
           $builder->whereIn('status',[0,1]);
        });
    }
}
