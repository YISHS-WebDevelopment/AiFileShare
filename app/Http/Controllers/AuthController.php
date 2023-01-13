<?php

namespace App\Http\Controllers;

use App\Circle;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Circle $circle)
    {
        return view('register', compact(['circle']));
    }

    public function login()
    {
        return view('login');
    }

    public function registerAction(Request $request)
    {
        $request->validate(
            [
                'id' => ['unique:users','regex:/^[a-zA-Z\s]+/','min:4'],
                'password' => 'min:3',
                'username' => ['regex:/^[가-힣\s]+/','min:2'],
                'grade' => 'required',
                'group' => 'required',
                'circle_id' => 'required',
            ],
            [
                'id.unique' => '이미 등록된 아이디입니다.',
                'id.regex' => '아이디는 영어만 입력가능합니다.',
                'id.min' => '아이디는 최소 4자 이상 입력해주세요.',
                'password.min' => '비밀번호는 최소 3자 이상 입력해주세요.',
                'username.regex' => '이름은 한글만 입력 가능합니다.',
                'username.min' => '이름은 최소 두 글자 이상 입력해주세요.',
                'grade.required' => '학년을 선택해주세요.',
                'group.required' => '반을 선택해주세요.',
                'circle_id.required' => '동아리를 선택해주세요.',
            ]);

        $number = $request->number < 10 ? '0' . $request->number : $request->number;
        $student_id = $request->grade . $request->group . $number;

        if (User::where('student_id', $student_id)->count() > 0) return back()->withErrors('이미 등록된 학번입니다.');

        User::create([
            'id' => $request->id,
            'password' => $request->password,
            'username' => $request->username,
            'circle_id' => $request->circle_id,
            'grade_id' => $request->grade,
            'student_id' => $student_id
        ]);

        return redirect()->route('index')->with('msg','회원가입이 완료되었습니다.');
    }

    public function loginAction(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        if(!$user || $user->password !== $request->password) return back()->withErrors('아이디 또는 비밀번호가 일치하지 않습니다.');
        Auth::login($user);
        return redirect()->route('index')->with('msg', $user->username.'님 환영합니다.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('index')->with('msg','로그아웃이 완료되었습니다.');
    }
}
