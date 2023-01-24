<?php

namespace App\Http\Controllers;

use App\Board;
use App\comment;
use App\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function commentWrite(Request $request, Board $board, User $user)
    {
        if ($request['comment_id'] === null) {
            $input = [
                'contents' => $request['contents'],
                'user_id' => $user['id'],
                'board_id' => $board['id'],
            ];
            Comment::create($input);
            return back();
        } else if ($request['comment_id'] !== null) {
            $input = [
                'contents' => $request['contents'],
                'user_id' => $user['id'],
                'board_id' => $board['id'],
                'comment_id' => intval($request['comment_id']),
            ];
            Comment::create($input);
            return back();
        }

    }

    public function commentModify(Request $request,User $user){
        $comment = Comment::find($request->comment_id);
        if ($comment->user_id == auth()->user()->id || auth()->user()->type === 'admin'){
            $comment->update(['contents' => $request->contents]);
            return back();
        }
        else {
            return back()->with('msg','자기가 쓴 댓글만 수정 및 삭제가 가능합니다.');
        }
    }

    public function commentDelete($id){
        $comment = Comment::find($id);
        if ($comment->user_id == auth()->user()->id || auth()->user()->type === 'admin'){
            $comment->delete();
            return back();
        }
        else {
            return back()->with('msg','자기가 쓴 댓글만 수정 및 삭제가 가능합니다.');
        }
    }
}
