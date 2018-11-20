<?php

namespace App\Admin\Controllers;
use App\AdminUser;
class UserController extends Controller
{
    //管理列表页
    public function index()
    {
        $users = AdminUser::paginate(10);
        return view('admin/user/index',compact('users'));
    }
    //管理创建页
    public function create()
    {
        return view('admin/user/add');
    }
    //创建操做
    public function store()
    {
        $this->validate(request(),[
            'name'=>'required|min:3',
            'password'=>'required',
        ]);
        $name = request('name');
        $password = bcrypt(request('password'));
        AdminUser::create(compact('name','password'));
        return redirect('admin/users');
    }
    //用户角色页面
    public function role(\App\AdminUser $user)
    {
        $roles = \App\AdminRole::all();
        $myRoles = $user->roles;
        return view('admin/user/role',compact('user','roles','myRoles'));
    }
    //储存用户角色页
    public function storeRole(\App\AdminUser $user)
    {
        $this->validate(request(),[
            'roles' =>'required|array'
        ]);
        $roles = \App\AdminRole::findMany(request('roles'));
        $myRoles = $user->roles;
        //要增加的
        $addRoles = $roles->diff($myRoles);
        foreach ($addRoles as $role){
            $user->assignRole($role);
        }
        //要删除的

//        dd($roles,$myRoles);
        $deleteRoles = $myRoles->diff($roles);
        foreach ($deleteRoles as $role){
            $user->deleteRole($role);
        }
        return back();
    }
}