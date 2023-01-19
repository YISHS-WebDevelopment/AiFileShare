@extends('template.app')
@section('style')
    <link rel="stylesheet" href="{{asset('/public/css/mypage/style.css')}}">
@endsection
@section('script')
    <script>
        $(() => {
            event();
        })

        function event() {
            $(document)
                .on('change', '#profile', chg_profile)
                .on('change', '#password',chk_password)
                .on('change', '#password_chk',chk_password2)

        }
        function chk_password(){
            let keyword = $(this).val();
            let chk = $('#password_chk').val();
            if (keyword === chk){
                $('.chk_msg').text('비밀번호와 비밀번호 확인이 일치합니다');
                $('.chk_msg').css({'color':'green'});
            }
            else{
                $('.chk_msg').text('비밀번호와 비밀번호 확인이 일치하지 않습니다');
                $('.chk_msg').css({'color':'red'});
            }
        }
        function chk_password2(){
            let keyword = $(this).val();
            let chk = $('#password').val();
            if (keyword === chk){
                $('.chk_msg').text('비밀번호와 비밀번호 확인이 일치합니다');
                $('.chk_msg').css({'color':'green'});
            }
            else{
                $('.chk_msg').text('비밀번호와 비밀번호 확인이 일치하지 않습니다');
                $('.chk_msg').css({'color':'red'});
            }
        }

        async function chg_profile() {
            let img = $(this)[0].files;
            let img_file = img[0];
            let reader = new FileReader();
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
                    <label class="text-center font-weight-bold chg_profile" for="profile" style="color: #0000a9">프로필 사진
                        바꾸기</label>
                </div>
                <div class="d-flex flex-column" style="width: 50%">
                    <div class="mt-2">
                        <p class=" font-weight-bold">사용자 아이디</p>
                        <input type="text" class="form-control" name="auth_id" value="{{$user->auth_id}}">
                    </div>

                    <div class="mt-2">
                        <p class=" font-weight-bold">새 비밀번호</p>
                        <input type="password" class="form-control" id="password" name="password" value="{{$user->auth_id}}">
                    </div>

                    <div class="mt-2">
                        <p class="chk_msg"></p>
                        <p class=" font-weight-bold">새 비밀번호 확인</p>
                        <input type="password" class="form-control" id="password_chk" value="{{$user->auth_id}}">
                    </div>
                    <div>
                        <p>소속동아리</p>
                        <input type="text" disabled value="{{$user->circle->name}}">
                    </div>

                </div>

            </div>
        </div>
    </div>
    <div class="w-100 d-flex justify-content-center mt-5">
        <button class="btn btn-outline-primary">저장</button>
    </div>
    <input type="file" name="profile_img" accept="image/gif,image/jpeg,'image/png,'image/jpg" id="profile"
           style="visibility: hidden">

@endsection
