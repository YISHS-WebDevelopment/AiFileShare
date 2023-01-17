@extends('template.app')
@section('contents')
    <div class="align-items-center d-flex flex-column">
        <div class="d-flex">
            @foreach($circle->all() as $item)
                @if($item->detail !== 'all')
                    <a href="{{route('circles.detail',[$item->detail, 'all'])}}"><button class="btn btn-success mr-3">{{$item->name}}</button></a>
                @endif
            @endforeach
        </div>
        <a href="{{route('board.page',['null','all'])}}"><button class="btn btn-primary w-100 mt-3">게시판</button></a>
    </div>
@endsection
