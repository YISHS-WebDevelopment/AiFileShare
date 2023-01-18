@extends('template.app')
@section('style')
    <link rel="stylesheet" href="{{asset('/public/css/mypage/style.css')}}">
@endsection
@section('contents')
    <div class="container">
        <div class="d-flex justify-content-between">
            <div class="d-flex flex-column">
                <div class="profile">
                    <img src="{{asset('/storage/app/public/profile_img/default_profile.png')}}" alt="">
                </div>
                <p id="profile_chg" class="text-center mt-2" style="color: black;font-size: 18px;font-weight: bold">프로필
                    사진 바꾸기</p>
            </div>
            <div class="d-flex flex-column">
                <h2>이름</h2>
                <input type="text" class="form-control ml-2" placeholder="{{$user->username}}" value="{{$user->username}}">
                <div class="d-flex flex-column mt-2">
                    <h2>새 비밀번호</h2>
                    <input type="password" class="ml-2 form-control">
                    <h2 class="mt-3">새 비밀번호 확인</h2>
                    <input type="password" class="ml-2 form-control">
                </div>

            </div>
        </div>


        {{--        <div class="d-flex mt-2">--}}
        {{--            <h2>소개</h1>--}}
        {{--            <textarea name="" id="" cols="30" rows="10">소개</textarea>--}}
        {{--        </div>--}}
        <div class="d-flex">
            <h1>내가 쓴 글</h1>
            <hr>
            <h2>전체</h2>
            @foreach($boards as $board)

            @endforeach
        </div>
    </div>
@endsection
