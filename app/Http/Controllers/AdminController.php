<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    public function index()
    {
        return view('admin/index');
    }

    public function login()
    {
        return view('admin/login');
    }

    public function loginAction(Request $request)
    {
        $attempt_data = [
            'id' => $request['id'],
            'password' => $request['password'],
        ];
        $login = Auth::login($attempt_data);
        if ($login){
            return redirect(route('admin.index'))->with('msg','관리자 로그인 완료.');
        }
        return redirect(route('index'))->with('msg', '관리자 로그인 실패 확실하게 해주세요');
    }

    public function logout(){
        Auth::logout();
        return redirect(route('index'))->with('msg','로그아웃 되었습니다.');
    }
}
