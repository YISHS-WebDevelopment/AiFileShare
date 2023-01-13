@extends('template.app')
@section('contents')
    <div class="d-flex flex-column align-items-center ">
        <h2 class="mb-3 font-weight-bold">{{$detail}}({{$category === 'all' ? '전체' : $category.'학년'}})</h2>
        <div class="d-flex flex-column align-items-center">
            <div class="d-flex mb-3">
                <div class="btn-group btn-group-toggle board mr-3" data-toggle="buttons">
                    <label class="btn btn-secondary" onclick="location.href='{{route('circles.detail',[$detail, 'all'])}}'">
                        <input type="radio" name="options" id="option1" {{$category === 'all' ? 'checked' : ''}}>전체
                    </label>
                    <label class="btn btn-secondary" onclick="location.href='{{route('circles.detail',[$detail, '1'])}}'">
                        <input type="radio" name="options" id="option2" {{$category === '1' ? 'checked' : ''}}>1학년
                    </label>
                    <label class="btn btn-secondary" onclick="location.href='{{route('circles.detail',[$detail, '2'])}}'">
                        <input type="radio" name="options" id="option3" {{$category === '2' ? 'checked' : ''}}>2학년
                    </label>
                    <label class="btn btn-secondary" onclick="location.href='{{route('circles.detail',[$detail, '3'])}}'">
                        <input type="radio" name="options" id="option2" {{$category === '3' ? 'checked' : ''}}>3학년
                    </label>
                </div>
            </div>
            <div class="d-flex">
                <a href="{{route('circles.share',[$detail,$category])}}" class="mr-3"><button class="btn btn-success">공유폴더</button></a>
                <a href="{{route('board.detail',[$detail,$category])}}"><button class="btn btn-primary">게시판</button></a>
            </div>
    </div>
@endsection
