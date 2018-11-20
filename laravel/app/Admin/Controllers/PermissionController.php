<?php

namespace App\Admin\Controllers;
class PermissionController extends Controller
{
    //权限列表
    public function index()
    {
        $permissions = \App\AdminPermission::paginate(10);
        return view('admin.permission.index',compact('permissions'));
    }
    //权限创建页
    public function create()
    {
        return view('admin.permission.add');
    }
    //创建逻辑
    public function store()
    {
        $this->validate(request(),[
            'name' =>'required|min:3',
            'description'=>'required'
        ]);
        \App\AdminPermission::create(request(['description','name']));
        return redirect('/admin/permissions');
    }
}