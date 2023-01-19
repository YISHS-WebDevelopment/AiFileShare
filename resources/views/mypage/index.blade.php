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

            if (pwChk) {
                let res = window.confirm('변경사항을 정확히 기억하셨나요?');
                if (res) {
                    let form = $('<form></form>');
                    let auth_id = $('input[name=auth_id]');
                    let password = $('#password');
                    let img = $('#profile')[0].files[0];
                    let form_data = new FormData();
                    form_data.append('auth_id',auth_id);
                    form_data.append('password',password);
                    form_data.append('img',img);
                    console.log(form_data);
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        contentType:false,
                        processData:false,
                        cache: false,

                        url : '{{route('mypage.update')}}',
                        type:'put',
                        data :JSON.stringify(form_data),
                        success : function(res) {
                            console.log(res);
                        },
                        error : function(res) {
                            console.log(res);
                        }
                    })

                    // location.reload();
                }
            } else {
                alert('비밀번호와 비밀번호 확인이 맞지않습니다.');
            }

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
                        <img class="w-100 h-100" src="{{asset('/storage/app/public/profile_img/default_profile.png')}}"
                             alt="">
                    </div>
                    <p class="font-weight-bold text-center mt-2"
                       style="font-size: 21px">{{$user->student_id .'-'. $user->username}}</p>
                    <label class="text-center chg_profile" for="profile" style="color: #0000a9">프로필 사진
                        바꾸기</label>
                </div>
                <div class="d-flex flex-column" style="width: 50%">
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
                    <div class="mt-3">
                        <p>소속동아리</p>
                        <input type="text" disabled value="{{$user->circle->name}}">
                    </div>

                </div>

            </div>
        </div>
    </div>
    <div class="w-100 d-flex justify-content-center mt-5">
        <button class="btn btn-outline-primary" id="save">저장</button>
    </div>
    <div class="container mt-5">
        <h1>내가 쓴 글</h1>
        <hr>
        <div class="flex-column d-flex">

        </div>
    </div>
    <input type="file" name="profile_img" accept="image/gif,image/jpeg,'image/png,'image/jpg" id="profile"
           style="visibility: hidden">

@endsection
