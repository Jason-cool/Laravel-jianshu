@extends("layout.main")
@section("content")
    <div class="col-sm-8 blog-main">
        <form action="/posts" method="POST">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <div class="form-group">
                <label>标题</label>
                <input name="title" type="text" class="form-control" placeholder="这里是标题">
            </div>
            <div class="form-group">
                <label>内容</label>
                <textarea id="content"  style="height:400px;max-height:500px;" name="content" class="form-control" placeholder="这里是内容"></textarea>
            </div>
            @if(count($errors)>0)
                <div class="allert alert-danger" role="alert">
                    @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                    @endforeach
                </div>
            @endif
            <button type="submit" class="btn btn-default">提交</button>
        </form>
        <br>

    </div><!-- /.blog-main -->
@endsection
