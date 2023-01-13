@extends('template.app')
@section('style')
    <style>
        .board a {color: white}
    </style>
@endsection
@section('contents')
    <div class="d-flex flex-column align-items-center">
        @if($detail !== 'null')
            <h2 class="font-weight-bold">{{$detail}}게시판({{$category === 'all' ? '전체' : $category.'학년'}})</h2>
        @else
            <h2 class="font-weight-bold">{{$category === 'all' ? '전체' : $category.'학년'}}</h2>
            <div class="d-flex align-items-center mb-3">
                <div class="btn-group btn-group-toggle board mr-3" data-toggle="buttons">
                    <label class="btn btn-secondary" onclick="location.href='{{route('board.page',['null','all'])}}'">
                        <input type="radio" name="options" id="option1" {{$category === 'all' ? 'checked' : ''}}>전체
                    </label>
                    <label class="btn btn-secondary" onclick="location.href='{{route('board.page',['null','1'])}}'">
                        <input type="radio" name="options" id="option2" {{$category === '1' ? 'checked' : ''}}>1학년
                    </label>
                    <label class="btn btn-secondary" onclick="location.href='{{route('board.page',['null','2'])}}'">
                        <input type="radio" name="options" id="option3" {{$category === '2' ? 'checked' : ''}}>2학년
                    </label>
                    <label class="btn btn-secondary" onclick="location.href='{{route('board.page',['null','3'])}}'">
                        <input type="radio" name="options" id="option2" {{$category === '3' ? 'checked' : ''}}>3학년
                    </label>
                </div>
            @endif
                <div class="d-flex btn-group board btn-group-toggle mr-3" data-toggle="buttons">
                        <label onclick="location.href='{{url()->current()}}?sort=recent'" class="btn btn-success">
                            <input type="radio" name="sort" id="sort1" {{is_null($sort) ? 'checked' : ''}}{{$sort === 'recent' ? 'checked' : ''}}>최신 순
                        </label>
                        <label onclick="location.href='{{url()->current()}}?sort=like'" class="btn btn-success">
                            <input type="radio" name="sort" id="sort2" {{$sort === 'like' ? 'checked' : ''}}> 좋아요 순
                        </label>
                        <label onclick="location.href='{{url()->current()}}?sort=view'" class="btn btn-success">
                            <input type="radio" name="sort" id="sort3" {{$sort === 'view' ? 'checked' : ''}}> 조회 순
                        </label>
                </div>
            @if($detail === 'null')
                <a href="{{route('board.all.write',['null',$category,'all'])}}"><button class="btn btn-primary">글 쓰기</button></a>
            @else
                <a href="{{route('board.circles.write',[$detail, $category,'circles'])}}"><button class="btn btn-primary mb-3 mt-3">글 쓰기</button></a>
            @endif
        </div>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>순서</th>
                <th>작성자</th>
                <th>제목</th>
                <th>작성일</th>
                <th>좋아요 수</th>
                <th>조회 수</th>
            </tr>
            </thead>
            <tbody>
                @foreach($board as $index =>$item)
                    <tr style="cursor: pointer;" onclick="location.href='{{route('board.detail.view',[$item->id])}}'">
                        <td>{{$index + 1}}</td>
                        <td>{{$item->user->student_id}}{{$item->user->username}}</td>
                        <td>{{$item->title}}</td>
                        <td>{{$item->created_at}}</td>
                        <td>{{$item->like}}개</td>
                        <td>{{$item->views}}회</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
