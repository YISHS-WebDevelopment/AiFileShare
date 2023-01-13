@extends('template.app')
@section('contents')
    <div class="d-flex justify-content-center">
        <form action="{{route('login.action')}}" method="post" class="w-50 rounded shadow p-4">
            @csrf
            <h2 class="font-weight-bold">로그인</h2>
            <hr>
            <div>
                <p>Id</p>
                <input type="text" autofocus name="id" class="form-control" placeholder="아이디를 입력해주세요." required>
            </div>
            <hr>
            <div>
                <p>Pw</p>
                <input type="password" name="password" class="form-control" placeholder="비밀번호를 입력해주세요." required>
            </div>
            <hr>
            <div>
                <button class="btn btn-primary w-100" type="submit">확인</button>
            </div>
        </form>
    </div>
@endsection
