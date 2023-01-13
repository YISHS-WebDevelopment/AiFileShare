<?php

namespace App\Http\Controllers;

use App\Circle;
use Illuminate\Http\Request;

class CirclesController extends Controller
{
    public function view(Circle $circle)
    {

        return view('circles/circlesPage',compact(['circle']));

    }

    public function detail($detail, $category)
    {
        if($category !== 'all' && auth()->user()->grade_id !== $category) return back()->with('msg', '다른 학년의 게시판은 볼 수 없습니다.');

        return view('circles/circlesDetail', compact(['detail', 'category']));
    }

    public function sharePage($detail, $category)
    {
        return view('circles/circlesShare', compact(['detail','category']));
    }
}
