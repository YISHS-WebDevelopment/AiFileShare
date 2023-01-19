<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class MypageController extends Controller
{
    //
    public function index(User $user)
    {
        $post = $user->posts;
        $post_array = [];
        return view('mypage/index', compact(['user']));
    }

    public function update(Request $request, User $user)
    {
        dd($request);
    }
}
