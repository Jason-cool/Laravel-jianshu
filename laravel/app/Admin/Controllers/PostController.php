<?php

namespace App\Admin\Controllers;
use App\Post;
class PostController extends Controller
{
    public function index()
    {
        //withoutGlobalScope不使用全局scope
        $posts = Post::withoutGlobalScope('avaiable')->where('status',0)->orderBy('created_at','desc')->paginate(10);
        return view('admin.post.index',compact('posts'));
    }

    //
    public function status(Post $post)
    {
        //验证
        $this->validate(request(),[
            'status' =>'required|in:-1,1',
        ]);
        //逻辑
        $post->status = request('status');
        $post->save();
        //渲染
        return [
            'error' => 0,
            'msg' => ''
        ];
    }
}