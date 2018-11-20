<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
    //登录页面
    public function index(){
        return view('/login.index');
    }
    //登录行为
    public function login(){
        //验证
        $this->validate(request(),[
            'password' =>'required|min:5|max:10',
            'email' =>'required|email',
            'is_remember' =>'integer'
        ]);
        //逻辑
      //  dd(request()->all());
        $user = request(['email','password']);
        $is_remember = boolval(request('is_remember'));
        if(\Auth::attempt($user,$is_remember)){
          return  redirect('/posts');
        }
        //渲染
        return \Redirect::back()->withErrors('密码邮箱不匹配');
    }
    //登出行为
    public function logout(){
        \Auth::logout();
        return  redirect('/login');
    }

    public function welcome()
    {
        return redirect('/login');
    }
}
