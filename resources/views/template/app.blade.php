<!doctype html>
<html lang="kr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AI-FileShare</title>

    <link rel="stylesheet" href="{{asset('/public/vendor/bootstrap-4.4.1-dist/css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{asset('/')}}">
    <link rel="stylesheet" href="{{asset('/public/css/app.css')}}">
    @yield('style')
    <script src="https://kit.fontawesome.com/2efaf0dba8.js" crossorigin="anonymous"></script>
    <script src="{{asset('/public/vendor/jquery-3.6.0.js')}}"></script>
    <script src="{{asset('/public/vendor/bootstrap-4.4.1-dist/js/bootstrap.bundle.min.js')}}"></script>
    @yield('script')

    @include('template.alert')
</head>
<body>
<header class="shadow">
    <div class="container h-100 d-flex justify-content-between align-items-center">
        <div class="d-flex">
            <a href="{{route('index')}}" class="mr-5">홈</a>
            <a href="{{route('circles.page')}}" class="mr-5">동아리</a>
        </div>
        <div class="d-flex align-items-center">
            @auth
                <span class="mr-3">{{auth()->user()->student_id}}{{auth()->user()->username}}</span>
                <a href="{{route('logout')}}"><button class="btn btn-danger">로그아웃</button></a>
            @elseauth('admin')
                <span class="mr-3">{{auth()->guard('admin')->user()->name}}</span>
                <a href="{{route('admin.logout')}}"><button class="btn btn-danger">로그아웃</button></a>
            @endauth
            @guest
                    <a href="{{route('login.page')}}"><button class="btn btn-primary mr-3">로그인</button></a>
                    <a href="{{route('register.page')}}"><button class="btn btn-primary">회원가입</button></a>
            @endguest
        </div>
    </div>
</header>

    <div class="container padding-div">
        @yield('contents')
    </div>
</body>
</html>