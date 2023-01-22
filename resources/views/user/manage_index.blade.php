@extends('template.app')
@section('style')
    <style>
        .profile {
            width: 100px;
            height: 100px;
            overflow: hidden;
            border-radius: 100%;
        }
        .profile img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

    </style>
@endsection
@section('contents')
    <div class="d-flex justify-content-center">
        <div class="d-flex flex-column container">
            @foreach($user_arr as $key=>$user)
                <h3 class="mt-5">{{$key}}</h3>
                <hr>
                <div class="d-flex flex-column">
                    @foreach($user as $u)
                        <div class="d-flex mt-5 align-items-center" style="cursor: pointer" onclick="location.href='{{route('mypage.index',$u)}}'">
                            <div class="profile"><img src="/storage/app/{{$u->profile}}" alt=""></div>
                            <h5 class="ml-3">{{$u['student_id']}}-{{$u->username}}</h5>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
@endsection
