<?php

namespace App\Admin\Controllers;
use App\Notice;
class NoticeController extends Controller
{
    public function index()
    {
        $notices = \App\Notice::all();
        return view('/admin/notice/index',compact('notices'));
    }
    public function create()
    {
        return view('admin/notice/create');
    }
    //创建行为
    public function store()
    {
        $this->validate(request(),[
            'title'=>'required|string',
            'content'=>'required|string'
        ]);
        $notice = \App\Notice::create(request(['title','content']));
//        dd($notice);
        //具体的分发逻辑
        dispatch(new \App\Jobs\SendMessage($notice));
        return redirect('/admin/notices');
    }
}