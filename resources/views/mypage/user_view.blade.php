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
                <div style="margin-right: 200px">
                    <h1>소개글</h1>
                    <hr>
                    <p style="font-size: 28px">
                        {{$user->introduce}}
                    </p>
                </div>
            </div>
        </div>
    </div>




@endsection
