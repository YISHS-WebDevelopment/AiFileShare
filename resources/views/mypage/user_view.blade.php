@extends('template.app')
@section('style')
    <link rel="stylesheet" href="{{asset('/public/css/mypage/style.css')}}">
@endsection
@section('contents')
    <div class="d-flex justify-content-center">
        <div class="container w-100">
            <div class="d-flex justify-content-between">
                <div class="d-flex flex-column" style="width: 200px;">
                    <div class="profile">
                        <img class="w-100 h-100"
                             src="{{!is_bool($user['profile'] === "null") ? asset('/storage/app/public/profile_img/default_profile.png') : asset('/storage/app/'.$user->profile)}}"
                             alt="">

                    </div>
                    <div class="w-100 justify-content-center mt-3">
                        <h5 class="text-center">{{$user->student_id}}-{{$user->username}}</h5>
                    </div>
                </div>
                <div style="margin-right: 30%">
                    <h1>소개글</h1>
                    <hr>
                    <p style="font-size: 28px">
                        {{$user->introduce}}
                    </p>
                </div>
            </div>
        </div>
    </div>


    <div class="container mt-5">
        <h1>{{$user->username}}(이)가 쓴 글</h1>
        <hr>
        @foreach($post_arr as $key=>$post)
            @if(empty($post))
            @else
                <div class="d-flex flex-column mt-5">
                    <h3>{{$key}}</h3>
                    <hr>
                    @foreach($post as $p)
                        <div class="flex-column d-flex mb-3">
                            <div class="post d-flex align-items-center justify-content-between"
                                 onclick="location.href='{{route('board.detail.view',$p['id'])}}'">
                                <a class="ml-3" href="{{route('board.detail.view',$p['id'])}}">{{$p->title}}</a>
                                <div class="d-flex">
                                    <span class="mr-5">{{$p->user['username']}}</span>
                                    @php($date = explode('-',$p->created_at))
                                    @php($day = explode(' ',$date[2]))
                                    @php($time = explode(':',$date[2]))
                                    <span
                                        class="mr-5"> {{$date[0].'년 '.$date[1].'월 '.$day[0].'일 '. is_bool(intval($time[0]) <= 12) ? '오전 '.(intval($time[0])-12) :'오후'.intval($time[0]) }}{{'시 '.$time[1].'분 '.$time[2].'초'}}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        @endforeach
        <div class="d-flex flex-column mt-5">
            <h1>{{$user->username}}(이)가 만든 폴더</h1>
            <hr>
            @foreach($myfolders as $f)
                <div class="flex-column d-flex mb-3">
                    <div class="post d-flex align-items-center justify-content-between"
                         onclick="location.href='{{route('folder.index',[$f->circle->detail,$f['category'],$f['url']])}}'">
                        <a class="ml-3"
                           href="{{route('folder.index',[$f->circle->detail,$f['category'],$f['url']])}}">{{$f->title}}</a>
                        <div class="d-flex">
                            <span class="mr-5">{{$f->user['username']}}</span>
                            @php($date = explode('-',$f->created_at))
                            @php($day = explode(' ',$date[2]))
                            @php($time = explode(':',$date[2]))
                            <span
                                class="mr-5"> {{$date[0].'년 '.$date[1].'월 '.$day[0].'일 '. is_bool(intval($time[0]) <= 12) ? '오전 '.(intval($time[0])-12) :'오후'.intval($time[0]) }}{{'시 '.$time[1].'분 '.$time[2].'초'}}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="d-flex flex-column mt-5">
            <h1>{{$user->username}}(이)가 만든 파일</h1>
            <hr>
            @foreach($myfiles as $file)
                <div class="flex-column d-flex mb-3">
                    <div class="post d-flex align-items-center justify-content-between"
                         onclick="location.href='{{route('folder.index',[$file->folder->circle->detail,$file->folder['category'],$file->folder['url']])}}'">
                        <a class="ml-3"
                           href="{{route('folder.index',[$file->folder->circle->detail,$file->folder['category'],$file->folder['url']])}}">{{$file->title}}</a>
                        <p>{{$file->size}}</p>
                        <div class="d-flex">
                            <span class="mr-5">{{$file->folder->user['username']}}</span>
                            @php($date = explode('-',$file->folder->created_at))
                            @php($day = explode(' ',$date[2]))
                            @php($time = explode(':',$date[2]))
                            <span
                                class="mr-5"> {{$date[0].'년 '.$date[1].'월 '.$day[0].'일 '. is_bool(intval($time[0]) <= 12) ? '오전 '.(intval($time[0])-12) :'오후'.intval($time[0]) }}{{'시 '.$time[1].'분 '.$time[2].'초'}}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endsection
