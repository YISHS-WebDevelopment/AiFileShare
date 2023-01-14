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
            })
        </script>
@endsection
@section('contents')
    <div>
        <h2 class="font-weight-bold">{{$board->circle_check === 'null' ? '전체 게시판' : $board->circle_check.'게시판'}}</h2>
        <hr>
        <div class="d-flex flex-column">
            <div class="w-100 d-flex justify-content-between">
                <b class="mb-2" style="font-size: 1.1rem">{{$board->title}}</b>
                <div class="d-flex">
                    <button class="btn btn-outline-primary"
                            onclick="location.href='{{route('boardModifyPage',$board->id)}}'">수정
                    </button>
                    <button class="btn btn-outline-danger ml-2">삭제</button>
                </div>

            </div>
            <div class="d-flex">
                <span class="mr-2">{{$board->user->student_id}}{{$board->user->username}}</span>
                <span class="ml-2">{{$board->created_at}}</span>
            </div>
        </div>
        <hr>
        <div class="d-flex flex-column">
            <span>{!! $board->contents !!}</span>
        </div>
        <hr>
        <div class="btn-group">
            <button class="btn btn-primary" onclick="location.href='{{url()->previous()}}'">목록</button>
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
