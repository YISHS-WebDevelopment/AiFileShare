<?php

namespace App\Http\Controllers;

use App\Circle;
use App\User;
use Illuminate\Http\Request;

class MypageController extends Controller
{
    //
    public function index(User $user)
    {
        $posts = $user->posts;
        $circles = Circle::all();
        foreach ($circles as $circle) {
            $post_arr[$circle->detail] = [];
        }
        foreach ($posts as $post) {
            if ($post->circle_check === 'null') {
                array_push($post_arr['all'], $post);
            } else {
                foreach ($post_arr as $key => $circle) {
                    if ($post->circle_check == $key) {
                        array_push($post_arr[$key],$post);
                    }
                }
            }
        }
        return view('mypage/index', compact(['user','post_arr']));
    }

    public function update(Request $request, User $user)
    {
        dd($request);
    }
}
