@extends('template.admin_app')
@section('style')
    <link rel="stylesheet" href="{{asset('/public/css/folder/folder.css')}}">

@endsection
@section('contents')
    <div class="d-flex justify-content-center">
        <div class="container">
            <h1 class="text-center">폴더 및 파일 관리</h1>
            <div class="align-items-center d-flex flex-column mt-5">
                <div class="d-flex container w-100">
                    <div class="d-flex flex-column w-100" style="font-size: 40px">
                        @foreach($folders as $folder)
                            <div class="d-flex flex-column p-3 shadow w-100">
                                <div class="d-flex">
                                    {{--                                    <img src="{{asset('/public/images/folder_icon.svg')}}" alt="" >--}}
                                    <a href="">{{$folder->title}}</a>
                                </div>
                                @foreach($folder->childFolders($folder['url']) as $c)
                                    <div class="d-flex justify-content-between">
                                        <a href=""
                                           style="margin-left:{{$loop->index }}0px;font-size: {{38 - $loop->index }}px">L {{$c['title']}}</a>
                                        <div class="d-flex">
                                            <form method="post" action="{{route('folder_management.page')}}">
                                                @method('put')
                                                @csrf
                                                <button class="btn btn-outline-primary mr-2 mt-2">관리</button>
                                            </form>

                                            <form method="post" action="{{route('folder.delete')}}">
                                                @method('delete')
                                                @csrf
                                                <button class="btn btn-outline-danger mt-2" type="submit" value="{{$c['id']}}" name="id">삭제</button>
                                            </form>
                                        </div>
                                    </div>

                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
