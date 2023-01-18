<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function mypageIndex(User $user)
    {
        $boards  = [
            'ALL' => [],
            'first' => [],
            'second' => [],
            'third' => [],
        ];
        foreach ($user->boards as $board){
            if ($board->category === 'all'){
                array_push($boards['ALL'],$board);
            }
            else if($board->category == '1'){
                array_push($boards['first'],$board);
            }
            else if($board->category == '2'){
                array_push($boards['second'],$board);
            }
            else if($board->category == '3'){
                array_push($boards['third'],$board);
            }
        }
        return view('/mypage/index',compact(['user','boards']));
    }

}
