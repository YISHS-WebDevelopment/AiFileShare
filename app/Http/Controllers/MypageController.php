<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class MypageController extends Controller
{
    //
    public function index(User $user){
        return view('mypage/index',compact(['user']));
    }
}
