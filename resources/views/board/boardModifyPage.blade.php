@extends('template.app')
@section('style')
    <style>
        .danger-mark {
            color: red;
            font-size: 1.2rem;
        }

        .danger-mark ~ span {
            color: #aaa;
            font-size: .9rem;
        }
        .note-group-image-url{
            display: none;
        }

    </style>
    <link rel="stylesheet" href="{{asset('./public/vendor/summernote/css/summernote/summernote-lite.css')}}">

@endsection
@section('script')
    <script src="{{asset('./public/vendor/summernote/js/summernote/summernote-lite.js')}}"></script>
    <script src="{{asset('./public/vendor/summernote/js/summernote/lang/summernote-ko-KR.js')}}"></script>
    <script>
        $(document).ready(function () {
            //여기 아래 부분
            $('#summernote').summernote({
                height: 1500,                 // 에디터 높이
                minHeight: null,             // 최소 높이
                maxHeight: null,             // 최대 높이
                focus: true,                  // 에디터 로딩후 포커스를 맞출지 여부
                lang: "ko-KR",					// 한글 설정
                placeholder: '최대 2048자까지 쓸 수 있습니다',//placeholder 설정
                toolbar:  [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                ],
            });
        });
    </script>
@endsection

@section('contents')
    <div class="d-flex justify-content-center">
                    <form action="{{route('boardModify.action',$board)}}" method="post"
                          enctype="multipart/form-data" class="w-100 rounded shadow p-4">
                        @csrf
                        @method('put')
                        <div class="d-flex flex-column">
                            <div class="d-flex justify-content-between">
                                <h2 class="font-weight-bold">글 쓰기({{$board->category === 'all' ? '전체' : $category.'학년'}})</h2>
                                <button class="btn btn-sm btn-secondary" type="button"
                                        onclick="location.href='{{url()->previous()}}'">목록으로
                                </button>
                            </div>
                            <div class="d-flex">
                                <span class="danger-mark">*</span><span>는 필수 입력 사항입니다.</span>
                            </div>
                        </div>
                        <hr>
                        <div>
                            <div class="d-flex">
                                <span class="danger-mark">*</span>
                                <p>제목</p>
                            </div>
                            <input type="text" name="title" class="form-control" required placeholder="제목을 입력해주세요." value="{{$board->title}}">
                        </div>
                        <hr>
                        <div>
                            <div class="d-flex">
                                <span class="danger-mark">*</span>
                                <p>내용</p>
                            </div>
                            <textarea name="editordata" id="summernote" cols="30" rows="10" required
                                      placeholder="내용을 입력해주세요." class="form-control">
                            {!! $board['contents'] !!}
                            </textarea>
                        </div>
                        <hr>
                        <button class="btn btn-primary w-100" type="submit">수정</button>
                    </form>
    </div>
@endsection
