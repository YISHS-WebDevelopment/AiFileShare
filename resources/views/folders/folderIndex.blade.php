@extends('template.app')

@section('script')
    <script src="{{asset('/public/js/circles/folder.js')}}"></script>
    @include('folders.folderAjax')
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
        <div class="container">
            <div class="d-flex justify-content-between">
                <div class="d-flex flex-column path-box">
                    @if(!$url)
                        <h1 style="cursor: pointer"><a href="#">.. </a></h1>
                    @else
                        <h1>
                            @if(!$parent)
                                <a href="{{route('folder.index',[$detail,$category])}}">..</a>
                            @else
                                <a href="{{route('folder.index',[$parent->circle->detail,$category, $parent->url])}}">..</a>
                            @endif
                            @foreach($parent_arr['path'] as $folder)
                                <a href="{{route('folder.index', [$folder->circle->detail, $category, $folder->url])}}">/{{$folder->title}}</a>
                            @endforeach
                        </h1>
                    @endif
                    <div class="d-flex">
                        <span id="important-icon">*</span><span id="read-text">상위 폴더로 이동하려면 ..이나 폴더 이름을 클릭해주세요.</span>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="dropdown show">
                        <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
            <span id="important-icon">*</span><span id="read-text">빈 폴더는 zip폴더에 포함 되지 않습니다.</span>
        </div>
        <table class="table pl-4 pr-4 rounded shadow list-table">
            <thead>
            <tr>
                <th><i id="file-icon" class="fa-solid fa-file"></i></th>
                <th>
                    <div class="dropdown show w-100 h-100">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">
                            이름
                            @if($sort === 'textAsc')
                                ↑
                            @elseif($sort === 'textDesc')
                                ↓
                            @endif
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="?sort=textAsc">텍스트 오름차순</a>
                            <a class="dropdown-item" href="?sort=textDesc">텍스트 내림차순</a>
                        </div>
                    </div>
                </th>
                <th>
                    <div class="dropdown show">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">
                            수정한 날짜
                            @if($sort === 'old')
                                ↑
                            @elseif($sort === 'recent')
                                ↓
                            @endif
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="?sort=old">오래된 순</a>
                            <a class="dropdown-item" href="?sort=recent">최신 순</a>
                        </div>
                    </div>
                </th>
                <th>
                    <div class="dropdown show">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">
                            파일 크기
                            @if($sort === 'sm')
                                ↑
                            @elseif($sort === 'big')
                                ↓
                            @endif
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="?sort=sm">작은 숫자순</a>
                            <a class="dropdown-item" href="?sort=big">큰 숫자순</a>
                        </div>
                    </div>
                </th>
                <th>작성자</th>
                <th>메뉴</th>
            </tr>
            </thead>
            <tbody>
            @foreach($allFolderAndFiles as $item)
                <tr>
                    @if(class_basename($item) === 'Folder')
                        <td><img src="{{asset('/public/images/folder_icon.svg')}}" class="folder-icon"
                                 alt="folder_icon"></td>
                        <td>
                            <a id="folder_{{$item->id}}"
                               href="{{route('folder.index',[$detail,$category,$item->url])}}">{{$item->title}}</a>
                        </td>
                        <td class="date-td">{{date('Y-m-d',strtotime($item->created_at))}}</td>
                    @else
                        <td><img src="{{asset('/public/images/txt_icon.svg')}}" class="folder-icon" alt="folder_icon">
                        </td>
                        <td>{{$item->title}}</td>
                        <td>{{date('Y-m-d',strtotime($item->created_at))}}</td>
                    @endif
                    <td>{{$item->sizeExplode($item->size)}}</td>
                    <td>{{$item->user->student_id}}{{$item->user->username}}</td>
                    <td>
                        <div class="dropdown show">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                @if(class_basename($item) === 'Folder')
                                    <a class="dropdown-item" href="#" id="rename" data-toggle="modal"
                                       data-target="#rename-modal" data-title="{{$item->title}}"
                                       data-id="{{$item->id}}">이름 바꾸기</a>
                                    @if(is_null($item->folder_id))
                                        <a class="dropdown-item move-btn" data-url="{{$url}}" data-id="null"
                                           data-tg="{{$item->id}}"
                                           href="#" data-toggle="modal" data-target="#move-modal" data-type="folder">다음으로
                                            이동</a>
                                    @else
                                        <a class="dropdown-item move-btn" data-url="{{$url}}"
                                           data-id="{{$item->folder_id}}" data-tg="{{$item->id}}"
                                           href="#" data-toggle="modal" data-target="#move-modal" data-type="folder">다음으로
                                            이동</a>
                                    @endif
                                    <a class="dropdown-item"
                                       href="{{route('folder.zip.down',[$item->id])}}">다운로드</a>
                                    <a class="dropdown-item" href="{{route('folder.delete',[$item->id])}}"
                                       onclick="return confirm('정말 삭제하시겠습니까? 하부 폴더와 파일들이 모두 삭제됩니다.')">삭제</a>
                                @else
                                    @if(is_null($item->folder_id))
                                        <a class="dropdown-item move-btn" data-url="{{$url}}" data-id="null"
                                           data-tg="{{$item->id}}"
                                           href="#" data-toggle="modal" data-target="#move-modal" data-type="file">다음으로
                                            이동</a>
                                    @else
                                        <a class="dropdown-item move-btn" data-url="{{$url}}"
                                           data-id="{{$item->folder_id}}" data-tg="{{$item->id}}"
                                           href="#" data-toggle="modal" data-target="#move-modal" data-type="file">다음으로
                                            이동</a>
                                    @endif
                                    <a class="dropdown-item" href="{{route('file.download',[$item->id])}}">다운로드</a>
                                    <a class="dropdown-item" href="{{route('file.delete',[$item->id])}}"
                                       onclick="return confirm('정말 삭제하시겠습니까?')">삭제</a>
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            {{ $allFolderAndFiles->links() }}
        </div>
    </div>

    <div class="modal fade" id="share-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h4 class="modal-title">폴더 만들기</h4>
                    <span class="font-weight-bold" style="cursor: pointer;font-size: 1.2rem"
                          data-dismiss="modal">X</span>
                </div>
                <form action="{{route('folder.create',[$detail,$category,$url])}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="text" id="folder-input" placeholder="폴더 이름을 입력해주세요." name="title" required
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

                <form enctype="multipart/form-data" action="{{route('file.create', [$detail,$category,$url])}}"
                      method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="d-flex">
                            <span id="important-icon">*</span><span id="read-text">Ctrl/Shift로 다중 선택이 가능합니다.</span>
                        </div>
                        <div class="d-flex">
                            <span id="important-icon">*</span><span id="read-text">파일은 한 번에 최대 10개까지 업로드 가능합니다.</span>
                        </div>
                        <input type="file" name="file[]" class="file-input form-control" multiple required>
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

    @include('folders.folderMove')
@endsection
