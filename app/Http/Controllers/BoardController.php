<?php

namespace App\Http\Controllers;

use App\Board;
use App\Boards_like;
use App\Boards_view;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function view(Request $request,$detail,$category)
    {
        $sort = $request->sort;
        $board = Board::where('circle_check', $detail)->where('category', $category);
        if(is_null($sort) || $sort === 'recent') $board = $board->orderByDesc('created_at')->get();
        else if($sort === 'like') $board = $board->orderByDesc('like')->get();
        else $board = $board->orderByDesc('views')->get();

        if($category !== 'all' && auth()->user()->grade_id !== $category) return back()->with('msg', '다른 학년의 게시판은 볼 수 없습니다.');

        return view('board/boardPage',compact(['detail', 'category','board', 'sort']));
    }

    public function writePage($detail, $category, $type)
    {
        return view('board/boardWritePage', compact(['detail','category','type']));
    }

    public function writeAction(Request $request, $detail, $category ,$type)
    {
        Board::create([
            'user_id' => auth()->user()->id,
            'circle_check' => $detail,
            'category' => $category,
            'title' => $request->title,
            'contents' => $request->contents
        ]);

        if($type === 'circles') return redirect()->route('board.page',[$detail, $category])->with('msg', '작성이 완료되었습니다.');
        else return redirect()->route('board.page',['null',$category])->with('msg', '작성이 완료되었습니다.');
    }

    public function detailView($id)
    {
        $board = Board::where('id', $id)->first();
        $view = Boards_view::where(['user_id' => auth()->user()->id, 'board_id' => $board->id]);
        if(!$view->exists()) {
            Boards_view::create([
                'user_id' => auth()->user()->id,
                'board_id' => $board->id,
                'views' => 1
            ]);
        }
        $board->update([
           'views' => Boards_view::where('board_id', $board->id)->count(),
        ]);
        return view('board/boardDetailView',compact(['board']));
    }

    public function likeClick(Request $request)
    {
        $board_like = Boards_like::where(['user_id' => $request->user_id, 'board_id' => $request->board_id]);
        if($board_like->exists()) {
            if($board_like->first()->like === 1) {
                $board_like->update([
                    'like' => 0,
                ]);
            } else {
                $board_like->update([
                    'like' => 1,
                ]);
            }
        } else {
            Boards_like::create([
                'user_id' => $request->user_id,
                'board_id' => $request->board_id,
                'like' => 1
            ]);
        }
        Board::where('id',$request->board_id)->update([
            'like' => Boards_like::where(['like' => 1, 'board_id' => $request->board_id])->count()
        ]);

        return [
            'board' => Board::where('id', $request->board_id)->first(),
            'like' => $board_like->first(),
        ];
    }
    public function manage_index(){

    }
}
