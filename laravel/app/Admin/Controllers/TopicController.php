<?php

namespace App\Admin\Controllers;
use App\Topic;
class TopicController extends Controller
{
    public function index()
    {
        $topics = \App\Topic::all();
        return view('/admin/topic/index',compact('topics'));
    }
    public function create()
    {
        return view('admin/topic/create');
    }
    //创建行为
    public function store()
    {
        $this->validate(request(),[
            'name'=>'required|string'
        ]);
        $name = request('name');
        Topic::create(['name'=>$name]);
        return redirect('/admin/topics');
    }
    public function destroy(Topic $topic)
    {
        $topic->delete();
        return [
            'error'=>0,
            'msg'  =>''
        ];
    }
}