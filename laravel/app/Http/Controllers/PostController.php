<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Comment;
use App\Zan;

class PostController extends Controller
{
    //列表
    public function index() {

//        $res = array( 0=> array('id' => 0,'name' => 'jason' ,'time'=>'2014-10-18'),
//                       1=> array('id' => 2,'name' => 'jason' ,'time'=>'2014-10-18')
//            );
//        $res1 = array();
//        foreach ($res as $k=>$v){
//           $res1[] = $v['name'];
//        }
//        return $res1;
        $posts = new Post();
        //依赖注入\Psr\Log\LoggerInterface $log
        //使用门脸
//        \Log::info('post_index',['data'=>'this is post index3']);
//        使用容器
//        $app = app();
//        $log = $app->make('log');
//        $log->info('post_index',['data'=>'this is post index1']);
       /* $posts=[
            ['title'=>'this is title1!']  ,
            ['title'=>'this is title2!']  ,
            ['title'=>'this is title3!']  ,
        ];*/
       //加paginate为了分页用
        //withCount 是为了获取评论条数 模板渲染直接使用{{$post->comments_count}}
       $posts = Post::orderBy('created_at','desc')->withCount(['comments','zans'])->paginate(6);
//        dd($posts);
       //使用load 和 with 可以进行优化
      //  ps:$posts = Post::orderBy('created_at','desc')->withCount(['comments','zans'])->with('user')->paginate(6);
        $posts->load('user');
//      dd($posts);
        //return view('post/index',['posts'=>$posts]);
        return view('post/index',compact('posts'));
    }
    //详情页面
    public function show(Post $post) {
//        dd($post);
//      return view('post/show',['title'=>'this is title!','isShow'=>false]);
        $post->load('comments');//预加载，view层就不需要查询了
        return view('post/show',compact('post'));
    }
    //创建页面
    public function create() {
        return view("post/create");
    }
    //创建逻辑
    public function store() {
//        $params = ['title'=>request('title'),'content'=>request('content')];
        //验证
        $this->validate(request(), [
            'title' => 'required|string|max:100|min:5',
            'content' => 'required|string|min:5',
        ]);
        //逻辑
        $user_id = \Auth::id();
        $params = array_merge(request(['title','content']),compact('user_id'));
        Post::create($params);
        //渲染
        return redirect('/posts');
    }
    //编辑页面
    public function edit(Post $post) {
        return view('post/edit',compact('post'));
    }
    //编辑逻辑
    public function update(Post $post) {
        //验证
        $this->validate(request(), [
            'title' => 'required|string|max:100|min:5',
            'content' => 'required|string|min:5',
        ]);
        //策略
        $this->authorize('update', $post);
        //逻辑
        $post -> title = request('title');
        $post -> content = request('content');
        $post -> save();
        //渲染
        return redirect('/posts/'.$post->id);
    }
    //删除逻辑
    public function delete(Post $post) {
        //策略
        $this->authorize('delete', $post);
        $post->delete();
        return redirect('/posts');
    }
    //上传图片
    public function imagUupload(Request $request) {
        $path=$request->file('wangEditorH5File')->storePublicly(md5(time()));
        return asset('storage/'.$path);
        //dd(request()->all());
    }
    //提交评论
    public function comment(Post $post)
    {
        //验证
        $this->validate(request(),[
            'content' =>'required|min:3'
        ]);
        //逻辑
        $comment = new Comment();
        $comment->user_id = \Auth::id();
        $comment->content = request('content');
        $post->comments()->save($comment);
        //渲染
//       return \redirect::back();
        return back();
    }
    //点赞
    public function zan(Post $post)
    {
        $param = [
            'user_id' =>\Auth::id(),
            'post_id' =>$post->id
        ];
       Zan::firstOrCreate($param);
       return back();
    }
    //取消赞
    public function unzan(Post $post)
    {
        $post->zan(\Auth::id())->delete();
        return back();
    }

    //搜索
    public function search()
    {
        //验证

        $this->validate(request(),[
            'query' =>'required'
        ]);
        //逻辑
        $query = request('query');
        $posts = \App\Post::search($query)->paginate(2);
        //渲染
        return view('post/search',compact('posts','query'));
    }
}
