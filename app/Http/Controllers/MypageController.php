<?php

namespace App\Http\Controllers;

use App\Circle;
use App\File;
use App\Folder;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
                        array_push($post_arr[$key], $post);
                    }
                }
            }
        }
        $myfolders = Folder::where('user_id', $user['id'])->get();
        $myfiles = File::where('user_id', $user['id'])->get();
        if (auth()->user()->id == $user->id || auth()->user()->type === 'admin') {
            return view('mypage/index', compact(['user', 'post_arr', 'myfolders', 'myfiles']));
        } else {
            return view('mypage/user_view', compact(['user', 'post_arr', 'myfolders', 'myfiles']));
        }

    }

    public function update(Request $request, User $user)
    {
        if (!is_null($request->profile_img)) {
            $file_data = $_FILES['profile_img'];
            $path = $request->profile_img->storeAs('public/profile_img', time() . '_' . $file_data['name']);
            if ($user->path !== 'public/profile_img/default_profile.png') {
                Storage::delete($user->profile);
            }
            $input = [
                'profile' => $path
            ];
            $user->update($input);
        }
        if (!is_null($request['auth_id'])) {
            $user->update(['auth_id' => $request['auth_id']]);
        }
        if (!is_null($request['introduce'])) {
            $user->update(['introduce' => $request['introduce']]);
        }
        if (!is_null($request['password'])) {
            $user->update(['password' => $request['auth_id']]);
        }
        return redirect(route('mypage.index', $user));
    }
}
