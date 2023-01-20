@extends('template.app')
@section('style')
    <link rel="stylesheet" href="{{asset('/public/css/mypage/style.css')}}">
@endsection
@section('script')
    <script>
        let pwChk = false;
        $(() => {
            event();
        })

        function event() {
            $(document)
                .on('change', '#profile', chg_profile)
                .on('change', '#password', chk_password)
                .on('change', '#password_chk', chk_password2)
                .on('click', '#save', form_send)
        }

        function form_send() {

            {{--if (pwChk) {--}}
            {{--    let res = window.confirm('변경사항을 정확히 기억하셨나요?');--}}
            {{--    if (res) {--}}
            {{--        let form = $('<form method="post" action="{{route('user.update',$user)}}" enctype="multipart/form-data" ><form>')[0];--}}
            {{--        let auth_id = $('input[name=auth_id]')[0];--}}
            {{--        let password = $('#password')[0];--}}
            {{--        let img = $('#profile')[0];--}}
            {{--        form.append('auth_id', auth_id);--}}
            {{--        form.append('password', password);--}}
            {{--        let formData = new FormData(form);--}}
            {{--        formData.append('img', img.files[0]);--}}
            {{--        $.ajax({--}}
            {{--            headers: {--}}
            {{--                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
            {{--            },--}}
            {{--            url: '{{route('user.update',$user)}}',--}}
            {{--            type: 'post',--}}
            {{--            data: formData,--}}
            {{--            success: function (res) {--}}
            {{--                console.log(res);--}}
            {{--            },--}}
            {{--            error: function (res) {--}}
            {{--                console.log(res);--}}
            {{--            },--}}
            {{--            cache: false,--}}
            {{--            contentType: false,--}}
            {{--            processData: false--}}
            {{--        });--}}

            {{--        // location.reload();--}}
            {{--    }--}}
            {{--} else {--}}
            {{--    alert('비밀번호와 비밀번호 확인이 맞지않습니다.');--}}
            {{--}--}}

        }

        function chk_password() {
            let keyword = $(this).val();
            let chk = $('#password_chk').val();
            if (keyword === chk) {
                $('.chk_msg').text('비밀번호와 비밀번호 확인이 일치합니다');
                $('.chk_msg').css({'color': 'green'});
                pwChk = true;
            } else {
                $('.chk_msg').text('비밀번호와 비밀번호 확인이 일치하지 않습니다');
                $('.chk_msg').css({'color': 'red'});
                pwChk = false;
            }
        }

        function chk_password2() {
            let keyword = $(this).val();
            let chk = $('#password').val();
            if (keyword === chk) {
                $('.chk_msg').text('비밀번호와 비밀번호 확인이 일치합니다');
                $('.chk_msg').css({'color': 'green'});
                pwChk = true;
            } else {
                $('.chk_msg').text('비밀번호와 비밀번호 확인이 일치하지 않습니다');
                $('.chk_msg').css({'color': 'red'});
                pwChk = false;
            }
        }

        const readFile = (img) => {
            return new Promise((res) => {
                const reader = new FileReader();
                reader.onload = () => {
                    res(reader);
                }
                reader.readAsDataURL(img);
            })
        }

        async function chg_profile() {
            const default_path = '/storage/app/public/profile_img/default_profile.png'
            let img = $(this)[0].files;
            let img_file = img[0];
            let res = await readFile(img_file);
            res = res.result;
            $('.profile img').attr('src', res);
        }
    </script>
@endsection
@section('contents')
    <div class="d-flex justify-content-center">
        <div class="container w-100">
            <div class="d-flex justify-content-between">
                <div class="d-flex flex-column" style="width: 200px;">
                    <div class="profile">
                        <img class="w-100 h-100"
                             src="{{!is_bool($user['profile'] === "null") ? asset('/storage/app/public/profile_img/default_profile.png') : asset('/storage/app/'.$user->profile)}}"
                             alt="">
                    </div>
                    <p class="font-weight-bold text-center mt-2"
                       style="font-size: 21px">{{$user->student_id .'-'. $user->username}}</p>
                    <label class="text-center chg_profile" for="profile" style="color: #0000a9">프로필 사진
                        바꾸기</label>
                </div>

                <div class="d-flex flex-column" style="width: 50%">
                    <form action="{{route('user.update',$user)}}" method="post" enctype="multipart/form-data">
                        @method('put')
                        @csrf
                        <div class="mt-2">
                            <p class=" font-weight-bold">사용자 아이디</p>
                            <input type="text" class="form-control" name="auth_id" value="{{$user->auth_id}}">
                        </div>

                        <div class="mt-2">
                            <p class=" font-weight-bold">새 비밀번호</p>
                            <input type="password" class="form-control" id="password" name="password"
                                   placeholder="새 비밀번호를 입력해주세요">
                        </div>

                        <div class="mt-2">
                            <p class="chk_msg"></p>
                            <p class=" font-weight-bold">새 비밀번호 확인</p>
                            <input type="password" class="form-control" id="password_chk" placeholder="새 비밀번호를 입력해주세요">
                        </div>
                        <div class="mt-2">
                            <p class="chk_msg"></p>
                            <p class=" font-weight-bold">소개글</p>
                            <input type="text" class="form-control" id=introduce" name="introduce"
                                   placeholder="소개글을 입력해주세요" value="{{$user->introduce}}">
                        </div>

                        <div class="mt-3">
                            <p>소속동아리</p>
                            <input type="text" disabled value="{{$user->circle->name}}">
                        </div>
                </div>

            </div>
        </div>
    </div>

    <input type="file" name="profile_img" accept="image/gif,image/jpeg,'image/png,'image/jpg" id="profile"
           style="visibility: hidden">
    <div class="w-100 d-flex justify-content-center mt-5">
        <button class="btn btn-outline-primary" id="save" type="submit">저장</button>
        </form>
        @if(auth()->user()->type == 'admin')
            <form action="{{route('user.delete',$user)}}" method="post">
                @method('delete')
                @csrf
                <button type="submit" class="btn btn-outline-danger">유저 삭제</button>
            </form>
        @endif

    </div>
    <div class="container mt-5">
        @if(auth()->user()->type === 'admin')
            <h1>{{$user->username}}(이)가 쓴 글</h1>
        @else
            <h1>내가 쓴 글</h1>
        @endif
        <hr>
        @foreach($post_arr as $key=>$post)
            @if(empty($post))
            @else
                <div class="d-flex flex-column mt-5">
                    <h3>{{$key}}</h3>
                    <hr>
                    @foreach($post as $p)
                        <div class="flex-column d-flex mb-3">
                            <div class="post d-flex align-items-center justify-content-between"
                                 onclick="location.href='{{route('board.detail.view',$p['id'])}}'">
                                <a class="ml-3" href="{{route('board.detail.view',$p['id'])}}">{{$p->title}}</a>
                                <div class="d-flex">
                                    <span class="mr-5">{{$p->user['username']}}</span>
                                    @php($date = explode('-',$p->created_at))
                                    @php($day = explode(' ',$date[2]))
                                    @php($time = explode(':',$date[2]))
                                    <span
                                        class="mr-5"> {{$date[0].'년 '.$date[1].'월 '.$day[0].'일 '. is_bool(intval($time[0]) <= 12) ? '오전 '.(intval($time[0])-12) :'오후'.intval($time[0]) }}{{'시 '.$time[1].'분 '.$time[2].'초'}}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        @endforeach
        <div class="d-flex flex-column mt-5">
            @if(auth()->user()->type === 'admin')
                <h1>{{$user->username}}(이)가 만든 폴더</h1>
            @else
                <h1>내가 만든 폴더</h1>
            @endif
            <hr>
            @foreach($myfolders as $f)
                <div class="flex-column d-flex mb-3">
                    <div class="post d-flex align-items-center justify-content-between"
                         onclick="location.href='{{route('folder.index',[$f->circle->detail,$f['category'],$f['url']])}}'">
                        <a class="ml-3"
                           href="{{route('folder.index',[$f->circle->detail,$f['category'],$f['url']])}}">{{$f->title}}</a>
                        <div class="d-flex">
                            <span class="mr-5">{{$f->user['username']}}</span>
                            @php($date = explode('-',$f->created_at))
                            @php($day = explode(' ',$date[2]))
                            @php($time = explode(':',$date[2]))
                            <span
                                class="mr-5"> {{$date[0].'년 '.$date[1].'월 '.$day[0].'일 '. is_bool(intval($time[0]) <= 12) ? '오전 '.(intval($time[0])-12) :'오후'.intval($time[0]) }}{{'시 '.$time[1].'분 '.$time[2].'초'}}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="d-flex flex-column mt-5">
            @if(auth()->user()->type === 'admin')
                <h1>{{$user->username}}(이)가 만든 파일</h1>
            @else
                <h1>내가 만든 파일</h1>
            @endif
            <hr>
            @foreach($myfiles as $file)
                <div class="flex-column d-flex mb-3">
                    <div class="post d-flex align-items-center justify-content-between"
                         onclick="location.href='{{route('folder.index',[$file->folder->circle->detail,$file->folder['category'],$file->folder['url']])}}'">
                        <a class="ml-3"
                           href="{{route('folder.index',[$file->folder->circle->detail,$file->folder['category'],$file->folder['url']])}}">{{$file->title}}</a>
                        <p>{{$file->size}}</p>
                        <div class="d-flex">
                            <span class="mr-5">{{$file->folder->user['username']}}</span>
                            @php($date = explode('-',$file->folder->created_at))
                            @php($day = explode(' ',$date[2]))
                            @php($time = explode(':',$date[2]))
                            <span
                                class="mr-5"> {{$date[0].'년 '.$date[1].'월 '.$day[0].'일 '. is_bool(intval($time[0]) <= 12) ? '오전 '.(intval($time[0])-12) :'오후'.intval($time[0]) }}{{'시 '.$time[1].'분 '.$time[2].'초'}}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endsection
