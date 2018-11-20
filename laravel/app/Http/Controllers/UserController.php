<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    //个人设置页面
    public function setting(){
        return view('/user.setting');
    }
    //个人设置逻辑
    public function settingStore(){

    }
    //个人中心页面
    public function show(User $user)
    {
        //这个人的信息 包括 关注/粉丝/文章数
        $user = User::withCount(['fans','stars','posts'])->find($user->id);

        //这个人的文章列表，去最新时间的十条
        $posts = $user->posts()->orderBy('created_at','desc')->take(10)->get();
        //这个人的关注用户， 包括关注用户的 关注/粉丝/文章数
        $stars = $user->stars();
        $susers = User::whereIn('id',$stars->pluck('star_id'))->withCount(['fans','stars','posts'])->get();
        //这个人的粉丝用户， 包括粉丝用户的 关注/粉丝/文章数
        $fans = $user->fans();
        $fusers = User::whereIn('id',$fans->pluck('fan_id'))->withCount(['fans','stars','posts'])->get();
        return view('/user.show',compact('user','posts','susers','fusers'));
    }
    //点击关注
    public function fan(User $user)
    {
       $me = \Auth::user();
       $me->doFan($user->id);
       return [
           'error'=>0,
           'msg'=>''
       ];
    }
    //取消关注
    public function unfan(User $user)
    {
        $me = \Auth::user();
        $me->doUnfan($user->id);
        return [
            'error'=>0,
            'msg'=>''
        ];
    }
}
