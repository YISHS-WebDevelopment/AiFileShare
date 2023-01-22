@extends('template.app')
@section('style')
    <style>
        .like-icon {
            width: 100px;
            height: 100px;
            border-radius: 100%;
            cursor: pointer;
        }

        .like-icon i {
            font-size: 3rem;
        }
    </style>
@endsection
@section('script')
    <script>
        let dir = false;
        $(() => {
            $(document)
                .on('click', '.like-icon', function () {
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: '{{route('board.like')}}',
                        type: 'post',
                        data: {'user_id': '{{auth()->user()->id}}', 'board_id': '{{$board->id}}'},
                        success: function (res) {
                            $('#like-num').html(res.board.like);
                            if (res.like.like) {
                                $('.like-icon').addClass('border-danger');
                                $('.like-icon i').css('color', 'red');
                            } else {
                                $('.like-icon').removeClass('border-danger');
                                $('.like-icon i').css('color', 'black');
                            }
                        },
                        error: function (res) {
                            // console.log(res.responseJSON.message);
                        }
                    })
                })
                .on('click', '#reply', function () {
                    $('input[name="comment_id"]').val($(this).val());
                    $('.reply_state p').html('<- 답글 다는중...');
                    $('.reply_state button').css({'display': 'flex'});
                    $('.reply_state button').html('취소');

                })
                .on('click', '#reply_cancel', function () {
                    $('input[name="comment_id"]').val('');
                    $('.reply_state p').html('');
                    $('.reply_state button').css({'display': 'none'});
                    $('.reply_state button').html('');

                })
                .on('click', '.reply_view', function () {
                    let id = $(this).attr('data-id');
                    if (dir){
                        $(`div[data-id="${id}"]`).css({'display':'none'});
                        chgDir();
                        dir = false;
                    }
                    else{
                        console.log('z');
                        $(`div[data-id="${id}"]`).css({'display':'block'});
                        chgDir();
                        dir = true;
                    }
                })
        })

        function chgDir() {
            if (dir) {
                $('.chg_dir').html('▼');
            }
            else{
                $('.chg_dir').html('▲');
            }
        }
    </script>
@endsection
@section('contents')
    <div>
        <h2 class="font-weight-bold">{{$board->circle_check === 'null' ? '전체 게시판' : $board->circle_check.'게시판'}}</h2>
        <hr>
        <div class="d-flex flex-column">
            <div class="w-100 d-flex justify-content-between">
                <b class="mb-2" style="font-size: 2.4rem">제목: {{$board->title}}</b>
                <div class="d-flex">
                    <button class="btn btn-outline-primary"
                            onclick="location.href='{{route('boardModifyPage',$board->id)}}'">수정
                    </button>
                    <button class="btn btn-outline-danger ml-2"
                            ONCLICK="location.href='{{route('board.delete',$board)}}'">삭제
                    </button>
                </div>

            </div>
            <div class="d-flex align-items-center">

                <div style="width: 100px;height: 100px;border-radius: 100%;overflow: hidden;">
                    <img class="w-100 h-100" style="object-fit: cover" src="/storage/app/{{$board->user->profile}}"
                         alt="">
                </div>
                <span class="mr-2 ml-4">{{$board->user->student_id}}{{$board->user->username}}</span>
                <span class="ml-2">{{$board->created_at}}</span>
            </div>
        </div>
        <hr>
        <div class="d-flex flex-column" style="min-height: 600px">
            <span>{!! $board->contents !!}</span>
        </div>
        <hr>
        <div class="btn-group">
            <button class="btn btn-primary" onclick="location.href='{{url()->previous()}}'">목록</button>
        </div>
        <div class="d-flex flex-column">
            <hr>
            @foreach($comments as $comment)
                @php($reply_chk = \App\Comment::where('comment_id',$comment['id'])->first())

                @php($reply = \App\Comment::where('comment_id',$comment['id'])->get())
                <div class="mt-5">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div style="width: 40px;height: 40px;border-radius: 100%;overflow: hidden;">
                                <img class="w-100 h-100" style="object-fit: cover"
                                     src="/storage/app/{{$comment->user->profile}}"
                                     alt="">
                            </div>
                            <h6 class="ml-2">{{$comment->user['student_id']}}{{$comment->user['username']}}</h6>
                        </div>
                        @php($date = explode('-',$comment->created_at))
                        @php($day = explode(' ',$date[2]))
                        @php($time = explode(':',$date[2]))
                        <span
                            class="mr-5"> {{$date[0].'년 '.$date[1].'월 '.$day[0].'일 '. is_bool(intval($time[0]) <= 12) ? '오전 '.(intval($time[0])-12) :'오후'.intval($time[0]) }}{{'시 '.$time[1].'분 '.$time[2].'초'}}</span>

                    </div>
                    <div class="p-2">
                        <p class="ml-3">{{$comment['contents']}}</p>
                    </div>
                    <div class="d-flex w-100 justify-content-between">
                        <div class="d-flex">
                            <div>
                                @if(!is_null($reply_chk))
                                    <div class="d-flex">
                                        <div class="d-flex">
                                            <p class="reply_view chg_dir" style="color: #47c51b;cursor: pointer" data-id="{{$comment['id']}}">▼</p>
                                            <div class="ml-3"
                                                 style="width: 20px;height: 20px;border-radius: 100%;overflow: hidden;">
                                                <img class="w-100 h-100" style="object-fit: cover"
                                                     src="/storage/app/{{$comment->user->profile}}"
                                                     alt="">
                                            </div>
                                            <p style="font-size: 14px;color: green;cursor: pointer" class="reply_view" data-id="{{$comment['id']}}">
                                                답글 {{count($reply)}}
                                                개</p>
                                        </div>
                                        <button class="btn ml-3 " id="reply" value="{{$comment['id']}}">답글</button>
                                    </div>
                                    <div class="d-flex flex-column mt-2">
                                        @foreach($reply as $r)
                                            <div style="font-size: 16px;display: none" class="w-100 ml-5 mt-3" data-id="{{$r['comment_id']}}">
                                                <div class="d-flex align-items-center justify-content-between w-100">
                                                    <div class="d-flex align-items-center">
                                                        <div
                                                            style="width: 40px;height: 40px;border-radius: 100%;overflow: hidden;">
                                                            <img class="w-100 h-100" style="object-fit: cover"
                                                                 src="/storage/app/{{$r->user->profile}}"
                                                                 alt="">
                                                        </div>
                                                        <h6 class="ml-2">{{$r->user['student_id']}}{{$r->user['username']}}</h6>
                                                    </div>
                                                    @php($date = explode('-',$r->created_at))
                                                    @php($day = explode(' ',$date[2]))
                                                    @php($time = explode(':',$date[2]))
                                                    <span
                                                        class="mr-2"> {{$date[0].'년 '.$date[1].'월 '.$day[0].'일 '. is_bool(intval($time[0]) <= 12) ? '오전 '.(intval($time[0])-12) :'오후'.intval($time[0]) }}{{'시 '.$time[1].'분 '.$time[2].'초'}}</span>
                                                </div>
                                                <div class="p-2">
                                                    <p class="ml-3">{{$r['contents']}}</p>
                                                </div>
                                                @if($comment->user['id'] == auth()->user()->id)
                                                    <div>
                                                        <button class="btn btn-outline-primary">수정</button>
                                                        <button class="btn btn-outline-danger ml-2">삭제</button>
                                                    </div>
                                                @endif
                                                <hr>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                        @if($comment->user['id'] == auth()->user()->id)
                            <div>
                                <button class="btn btn-outline-primary">수정</button>
                                <button class="btn btn-outline-danger ml-2">삭제</button>
                            </div>
                        @endif

                    </div>
                </div>
                <hr>
            @endforeach
        </div>
        <div class="d-flex flex-column">
            <hr>
            <div class="w-100">
                <div class="d-flex align-items-center">
                    <div style="width: 55px;height: 55px;border-radius: 100%;overflow: hidden;">
                        <img class="w-100 h-100" style="object-fit: cover" src="/storage/app/{{$board->user->profile}}"
                             alt="">
                    </div>
                    <h4 class="ml-2">{{$board->user->username}}</h4>
                    <div class="reply_state d-flex">
                        <p class=""></p>
                        <button class="btn" style="display: none;" id="reply_cancel"></button>
                    </div>
                </div>
                <form action="{{route('comment.write',[$board,auth()->user()])}}" method="post">
                    @csrf
                    @method('post')
                    <textarea class="w-100 mt-2" name="contents" id="" cols="30" rows="10"
                              placeholder="타인의 권리를 침해하거나 명예를 훼손하는 댓글은 운영원칙 및 관련 법률에 제제를 받을 수 있습니다.
또한 도배나 의미없는 댓글을 달면 관리자가 응징합니다." required></textarea>
                    <input type="hidden" name="comment_id">
                    <div class="w-100 d-flex justify-content-end">
                        <button class="btn btn-outline-primary" type="submit">등록</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="d-flex justify-content-center">
            <div class="w-25 p-4 d-flex justify-content-center">
                @if($board->current_like_user->first() && $board->current_like_user->first()->like)
                    <div
                        class="rounded-circle border border-danger like-icon d-flex flex-column justify-content-center align-items-center">
                    <span id="like-num">
                        {{$board->like}}
                    </span>
                        <i class="fa-solid fa-heart" style="color: red"></i>
                    </div>
                @else
                    <div
                        class="rounded-circle border like-icon d-flex flex-column justify-content-center align-items-center">
                    <span id="like-num">
                        {{$board->like}}
                    </span>
                        <i class="fa-solid fa-heart"></i>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
