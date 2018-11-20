<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Topic;

class TopicController extends Controller
{
    //专题页面
    public function show(Topic $topic)
    {
        //带文章数的专题
        $topic = Topic::withCount('postTopics')->find($topic->id);
        //该专题的文章列表
        $posts = $topic->posts()->orderBy('created_at','desc')->take(10)->get();
//        dd($posts);
        //属于我的文章但不属于该专题
        $myposts = \App\Post::authorBy(\Auth::id())->topicNotBy($topic->id)->get();
        return view('topic/show',compact('topic','posts','myposts'));
    }
    public function submit(Topic $topic)
    {
        $this->validate(request(),[
            'post_ids'=>'required|array',
        ]);
        $post_ids = request('post_ids');
        $topic_id = $topic->id;
        foreach ($post_ids as $post_id) {
            \App\PostTopic::firstOrCreate(compact('topic_id','post_id'));
        }
        return back();
    }
}
