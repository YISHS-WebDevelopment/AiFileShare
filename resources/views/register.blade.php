@extends('template.app')
@section('contents')
    <div class="d-flex justify-content-center">
        <form action="{{route('register.action')}}" method="post" class="w-50 rounded shadow p-4">
            @csrf
            <h2 class="font-weight-bold">회원가입</h2>
            <hr>
            <div>
                <p>Id</p>
                <input type="text" name="id" class="form-control" placeholder="Id를 입력해주세요." required>
            </div>
            <hr>
            <div>
                <p>Pw</p>
                <input type="password" name="password" class="form-control" placeholder="Pw를 입력해주세요." required>
            </div>
            <hr>
            <div>
                <p>이름</p>
                <input type="text" name="username" class="form-control" placeholder="이름을 입력해주세요." required>
            </div>
            <hr>
            <div>
                <p>학년</p>
                <select name="grade" id="grade" class="form-control">
                    <option value="">-----SELECT-----</option>
                    <option value="3">3학년</option>
                    <option value="2">2학년</option>
                    <option value="1">1학년</option>
                </select>
            </div>
            <hr>
            <div>
                <p>반</p>
                <select name="group" id="group" class="form-control">
                    <option value="">-----SELECT-----</option>
                    <option value="04">4반</option>
                    <option value="05">5반</option>
                </select>
            </div>
            <hr>
            <div>
                <p>번호</p>
                <input type="number" name="number" value="1" min="1" max="21" class="form-control">
            </div>
            <hr>
            <div>
                <p>동아리</p>
                <select name="circle_id" id="circle-select" class="form-control">
                    <option value="">-----SELECT-----</option>
                    @foreach($circle->all() as $item)
                        <option value="{{$item->circle}}">{{$item->circle}}</option>
                    @endforeach
                </select>
            </div>
            <hr>
            <div>
                <button class="btn btn-primary w-100" type="submit">확인</button>
            </div>
        </form>
    </div>
@endsection
