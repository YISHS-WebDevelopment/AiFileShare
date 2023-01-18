<?php

namespace App\Http\Controllers;

use App\Circle;
use App\Folder;
use Illuminate\Http\Request;

class CirclesController extends Controller
{
    public function view(Circle $circle)
    {
        return view('circles/circlesPage',compact(['circle']));

    }

    public function detail($detail, $category)
    {
        if($detail !== 'all' && auth()->user()->circle->detail !== $detail) return back()->with('msg', '다른 동아리페이지는 볼 수 없습니다.');
        if($category !== 'all' && auth()->user()->grade != $category) return back()->with('msg', '다른 학년의 게시판은 볼 수 없습니다.');

        return view('circles/circlesDetail', compact(['detail', 'category']));
    }

    public function sharePage($detail, $category)
    {
        $circle_id = Circle::where('detail', $detail)->first()->id;
        return view('circles/circlesShare', compact(['detail','category', 'circle_id']));
    }
}
