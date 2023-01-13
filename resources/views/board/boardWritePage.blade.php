@extends('template.app')
@section('style')
    <style>
        .danger-mark{
            color: red;
            font-size: 1.2rem;
        }
        .danger-mark ~ span {
            color: #aaa;
            font-size: .9rem;
        }
    </style>
@endsection
@section('contents')
    <div class="d-flex justify-content-center">
        @if($detail !== 'null')
            <form action="{{route('board.circles.write.action',[$detail, $category, $type])}}" method="post" enctype="multipart/form-data" class="w-50 rounded shadow p-4">
        @else
            <form action="{{route('board.all.write.action',['null',$category, $type])}}" method="post" enctype="multipart/form-data" class="w-50 rounded shadow p-4">
        @endif
                @csrf
            <div class="d-flex flex-column">
                <div class="d-flex justify-content-between">
                    <h2 class="font-weight-bold">글 쓰기({{$category === 'all' ? '전체' : $category.'학년'}})</h2>
                    <button class="btn btn-sm btn-secondary" type="button" onclick="location.href='{{url()->previous()}}'">목록으로</button>
                </div>
                <div class="d-flex">
                    <span class="danger-mark">*</span><span>는 필수 입력 사항입니다.</span>
                </div>
            </div>
            <hr>
            <div>
                <div class="d-flex">
                    <span class="danger-mark">*</span><p>제목</p>
                </div>
                <input type="text" name="title" class="form-control" required placeholder="제목을 입력해주세요.">
            </div>
            <hr>
            <div>
                <div class="d-flex">
                    <span class="danger-mark">*</span><p>내용</p>
                </div>
                <textarea name="contents" id="contents" cols="30" rows="10" required placeholder="내용을 입력해주세요." class="form-control"></textarea>
            </div>
            <hr>
            <div>
                <p>파일 첨부</p>
                <input type="file" name="path" class="form-control" multiple>
            </div>
            <hr>
            <button class="btn btn-primary w-100" type="submit">작성</button>
        </form>
    </div>
@endsection
