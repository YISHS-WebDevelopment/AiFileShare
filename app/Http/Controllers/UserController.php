<?php

namespace App\Http\Controllers;

use App\Circle;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('type', 'user')->get();
        $circles = Circle::all();
        $user_arr = [];
        foreach ($circles as $circle) {
            $user_arr[$circle->name] = [];
        }
        foreach ($users as $user) {
            foreach ($user_arr as $key => $circle) {
                if ($user->circle['name'] == $key) {
                    array_push($user_arr[$key], $user);
                }
            }
        }
        return view('/user/manage_index', compact(['user_arr']));
    }

    public function delete(User $user)
    {
        if (auth()->user()->type == 'admin') {
            $user->delete();
            return redirect('/')->with('msg', '유저 삭제가 완료되었습니다.');
        } else {
            return redirect('/')->with('msg', '!!!당신은 관리자가 아닙니다.!!!');
        }
    }
}
