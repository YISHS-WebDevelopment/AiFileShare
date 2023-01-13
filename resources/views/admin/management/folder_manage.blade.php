@extends('template.admin_app')
@section('contents')
    <div class="d-flex justify-content-center">
        <div class="container">
            <h1 class="text-center">폴더 및 파일 관리</h1>
            <div class="align-items-center d-flex flex-column mt-5">
                <div class="d-flex">
                    @foreach($circle->all() as $item)
                        <a href="{{route('folder_list.page',$item->circle)}}"><button class="btn btn-success mr-3">{{$item->name}}</button></a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
