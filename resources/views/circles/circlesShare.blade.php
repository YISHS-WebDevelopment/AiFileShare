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
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="font-weight-bold">{{$detail}}({{$category === 'all' ? '전체' : $category.'학년'}})</h1>
            <a href="{{route('circles.detail',[$detail, $category])}}">
                <button class="btn btn-secondary">←</button>
            </a>
        </div>
        <hr>
        <div class="d-flex justify-content-between">
            <div class="dropdown show">
                <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    ＋ 새로 만들기
                </a>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#share-modal">폴더</a>
                </div>
            </div>
        </div>
        <hr>
        <table class="table pl-4 pr-4 rounded shadow">
            <thead>
            <tr>
                <th><i id="file-icon" class="fa-sharp fa-file"></i></th>
                <th>
                    <div class="dropdown show">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">
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
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">
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
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">
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
                @if(auth()->user()->type === 'admin')
                    <th>관리</th>
                @endif
            </tr>
            </thead>
            <tbody>
            @foreach(\App\Folder::where(['folder_id' => null, 'circle_id' => $circle_id, 'category' => $category])->get() as $folder)
                <tr>
                    <td><img src="{{asset('/public/images/folder_icon.svg')}}" class="folder-icon" alt="folder_icon">
                    </td>
                    <td>
                        <a id="folder_{{$folder['id']}}"
                           href="{{route('folder.index',[$detail,$category,$folder['url']])}}">{{$folder->title}}</a>
                    </td>
                    <td>{{date('Y-m-d',strtotime($folder->created_at))}}</td>
                    <td>{{$folder->sizeExplode($folder->size)}}</td>
                    <td>{{$folder->user->student_id}}{{$folder->user->username}}</td>
                    <td>
                        <div class="dropdown show">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                               data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="false">
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="#" id="rename" data-toggle="modal"
                                   data-target="#rename-modal" data-title="{{$folder->title}}"
                                   data-id="{{$folder->id}}">이름 바꾸기</a>
                                <a class="dropdown-item" href="#">다음으로 이동</a>
                                <a class="dropdown-item" href="{{route('folder.zip.down',[$folder->id])}}">다운(ZIP)</a>
                                <form action="">
                                    <a class="dropdown-item" href="{{route('folder.delete',[$folder->id])}}"
                                       onclick="return confirm('정말 삭제하시겠습니까? 하부 폴더와 파일들이 모두 삭제됩니다.')">삭제</a>
                                </form>
                            </div>
                        </div>
                    </td>
                    @if(auth()->user()->type === 'admin')
                        <td class="d-flex">
                            <form action="{{route('folder_manage.page')}}" method="get">
                                @csrf
                                <button class="btn btn-outline-primary" type="submit">관리</button>
                            </form>
                            <form action="{{route('admin.folder.delete',$folder['id'])}}" method="post">
                                @csrf
                                @method('delete')
                                <button class="btn btn-outline-danger ml-2" value="{{$folder['id']}}"
                                        onclick="return confirm('정말 삭제하시겠습니까? 하부 폴더와 파일들이 모두 삭제됩니다.');" id="folder_del">
                                    삭제
                                </button>
                            </form>
                        </td>
                    @endif

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
                <form action="{{route('folder.create',[$detail,$category])}}" method="post">
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
