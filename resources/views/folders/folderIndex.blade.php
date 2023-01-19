@extends('template.app')

@section('script')
    <script src="{{asset('/public/js/circles/folder.js')}}"></script>
    <script>
        $(document)
            .on('click', '#rename-btn', function() {
                const rename = $('#rename');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url : '{{route('folder.rename')}}',
                    type : 'post',
                    data : {'id' : rename.attr('data-id'), 'title' : $('#rename-modal #folder-input').val()},
                    success : function(res) {
                        console.log(res);
                        rename.attr('data-title', res.title);
                        if(!res) return alert('중복되는 폴더 이름이 있습니다.');

                        $('#rename-modal').modal('hide');
                        $(`#folder_${res.id}`).html(res.title);
                    },
                    error : function(res) {
                        console.log(res);
                    }
                })
            })
    </script>
@endsection
@section('style')
    <link rel="stylesheet" href="{{asset('/public/css/folder/folder.css')}}">
@endsection
@section('contents')
    <div class="container">
        <h1 class="font-weight-bold">{{$detail}}({{$category === 'all' ? '전체' : $category.'학년'}})</h1>
        <hr>
        <div class="container">
            <div class="d-flex justify-content-between">
                <div class="d-flex flex-column">
                    <div class="d-flex">
                        <span id="important-icon">*</span><span id="read-text">상위 폴더로 이동하려면 ..을 클릭해주세요.</span>
                    </div>
                    @if(is_null($parent))
                        <h1><a href="{{route('circles.share',[$detail,$category])}}">..</a>/{{$find['title']}}</h1>
                    @else
                        @if(!is_null($path))
                            <h1><a href="{{route('folder.index',[$parent->circle->detail,$category,$parent['url']])}}">..</a>{{$path}}/{{$find['title']}}</h1>
                        @else
                            <h1><a href="{{route('folder.index',[$parent->circle->detail,$category,$parent['url']])}}">..</a>{{$path}}/{{$find['title']}}</h1>
                        @endif
                    @endif
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="dropdown show">
                        <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            ＋ 새로 만들기
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#share-modal">폴더</a>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#file-modal">파일</a>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
        </div>
        <div class="d-flex">
            <i class="fa-sharp fa-solid fa-up"></i>
        </div>
        <table class="table pl-4 pr-4 rounded shadow">
            <thead>
            <tr>
                <th><i id="file-icon" class="fa-solid fa-file"></i></th>
                <th>
                    <div class="dropdown show">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            이름 ↑
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="#">텍스트 오름차순</a>
                            <a class="dropdown-item" href="#">텍스트 내림차순</a>
                        </div>
                    </div>
                </th>
                <th>
                    <div class="dropdown show">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            수정한 날짜
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="#">오래된 순</a>
                            <a class="dropdown-item" href="#">최신 순</a>
                        </div>
                    </div>
                </th>
                <th>
                    <div class="dropdown show">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            파일 크기
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="#">작은 숫자순</a>
                            <a class="dropdown-item" href="#">큰 숫자순</a>
                        </div>
                    </div>
                </th>
                <th>작성자</th>
                <th>메뉴</th>
            </tr>
            </thead>
            <tbody>
            @foreach(\App\Folder::where('folder_id',$find['id'])->get() as $folder)
                <tr>
                    <td><img src="{{asset('/public/images/folder_icon.svg')}}" class="folder-icon" alt="folder_icon"></td>
                    <td>
                        <a id="folder_{{$folder['id']}}" href="{{route('folder.index',[$detail,$category,$folder['url']])}}">{{$folder->title}}</a>
                    </td>
                    @if(is_null($folder->updated_at))
                        <td>{{date('Y-m-d',strtotime($folder->created_at))}}</td>
                    @else
                        <td>{{date('Y-m-d',strtotime($folder->updated_at))}}</td>
                    @endif
                    <td>{{$folder->sizeExplode($folder->size)}}</td>
                    <td>{{$folder->user->student_id}}{{$folder->user->username}}</td>
                    <td>
                        <div class="dropdown show">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="#" id="rename" data-toggle="modal"
                                   data-target="#rename-modal" data-title="{{$folder->title}}"
                                   data-id="{{$folder->id}}">이름 바꾸기</a>
                                <a class="dropdown-item" href="#">다음으로 이동</a>
                                <a class="dropdown-item" href="{{route('folder.zip.down',[$detail,$category,$folder->id])}}">다운(ZIP)</a>
                                <a class="dropdown-item" href="{{route('folder.delete',[$folder->id])}}" onclick="return confirm('정말 삭제하시겠습니까? 하부 폴더와 파일들이 모두 삭제됩니다.')">삭제</a>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            @foreach($files as $file)
                <tr>
                    <td><img src="{{asset('/public/images/txt_icon.svg')}}" class="folder-icon" alt="folder_icon"></td>
                    <td>
                        {{$file['title']}}
                    </td>
                    <td>{{date('Y-m-d',strtotime($file->created_at))}}</td>
                    <td>{{$file->sizeExplode($file->size)}}</td>
                    <td>{{$file->user->student_id}}{{$file->user->username}}</td>
                    <td>
                        <div class="dropdown show">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="#">다음으로 이동</a>
                                <a class="dropdown-item" href="{{route('file.download',[$file->id])}}">다운로드</a>
                                <a class="dropdown-item" href="{{route('file.delete',[$file->id])}}" onclick="return confirm('정말 삭제하시겠습니까?')">삭제</a>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="share-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h4 class="modal-title">폴더 만들기</h4>
                    <span class="font-weight-bold" style="cursor: pointer;font-size: 1.2rem"
                          data-dismiss="modal">X</span>
                </div>
                <form action="{{route('folder.create',[$detail,$category,$find->url])}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="text" id="folder-input" placeholder="폴더 이름을 입력해주세요." name="title"
                               class="form-control">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="submit">만들기</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="modal fade" id="file-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h4 class="modal-title">파일 업로드</h4>
                    <span class="font-weight-bold" style="cursor: pointer;font-size: 1.2rem"
                          data-dismiss="modal">X</span>
                </div>
                <form enctype="multipart/form-data" action="{{route('file.create', $find->url)}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="file" name="file">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">업로드</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="rename-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h4 class="modal-title">이름 바꾸기</h4>
                    <span class="font-weight-bold" style="cursor: pointer;font-size: 1.2rem"
                          data-dismiss="modal">X</span>
                </div>
                <div class="modal-body">
                    <input type="text" id="folder-input" placeholder="폴더 이름을 입력해주세요." autofocus name="title"
                           class="form-control">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="submit" id="rename-btn">바꾸기</button>
                </div>
            </div>
        </div>
    </div>
@endsection
