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
}
