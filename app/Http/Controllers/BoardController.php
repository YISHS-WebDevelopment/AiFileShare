<?php

namespace App\Http\Controllers;

use App\Board;
use App\Board_image;
use App\Boards_image;
use App\Boards_like;
use App\Boards_view;
use App\Http\Controllers\Controller;
use App\Post_img;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function view(Request $request, $detail, $category)
    {
        $sort = $request->sort;
        $board = Board::where('circle_check', $detail)->where('category', $category);
        if (is_null($sort) || $sort === 'recent') $board = $board->orderByDesc('created_at')->get();
        else if ($sort === 'like') $board = $board->orderByDesc('like')->get();
        else $board = $board->orderByDesc('views')->get();

        if ($category != 'all' && auth()->user()->grade != $category) return back()->with('msg', '다른 학년의 게시판은 볼 수 없습니다.');

        return view('board/boardPage', compact(['detail', 'category', 'board', 'sort']));
    }

    public function modifyPage($id)
    {
        $board = Board::find($id);
        if (auth()->check()) {
            if ($board->user_id !== auth()->user()->id && auth()->user()->type !== 'admin') {
                return redirect(route('board.detail.view', $board->id))->with('msg', '관리자와 본인만 글 수정이 가능합니다.');
            }
        }
        return view('board/boardModifyPage', compact(['board']));
    }

    public function modifyAction(Request $request, Board $board)
    {

        $input = [
            'title' => $request->title,
            'contents' => $request->editordata
        ];
//        $content = $request->editordata;
//        $dom = new \DomDocument();
        /*        @$dom->loadHTML('<?xml encoding="utf-8" ?>' . $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);*/
//        $imageFile = $dom->getElementsByTagName('img');
//        dd($imageFile);
        $b = $board->update($input);
        return redirect(route('board.detail.view', $board->id))->with('msg', '수정되었습니다.');
    }

    public function writePage($detail, $category, $type)
    {
        return view('board/boardWritePage', compact(['detail', 'category', 'type']));
    }

    public function writeAction(Request $request, $detail, $category, $type)
    {
        $content = $request->editordata;
        $dom = new \DomDocument();
        @$dom->loadHTML('<?xml encoding="utf-8" ?>' . $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $imageFile = $dom->getElementsByTagName('img');
        foreach ($imageFile as $item => $image) {
            $data = $image->getAttribute('src');
            list($type, $data) = explode(';', $data);
            list(, $data) = explode(',', $data);
            $imgeData = base64_decode($data);
            $time = time() . $item . '.png';
            $image_name = '/board_img/' . 'img_' . $time;
            $path = storage_path() . '/app/public' . $image_name;
            file_put_contents($path, $imgeData);
            $srcName = '/storage/app/public' . $image_name;
            $image->removeAttribute('src');
            $image->setAttribute('src', $srcName);
        }
        $content = $dom->saveHTML();

        $board = Board::create([
            'user_id' => auth()->user()->id,
            'circle_check' => $detail,
            'category' => $category,
            'title' => $request->title,
            'contents' => $content
        ]);
        if (count($imageFile) !== 0) {
            $input2 = [
                'board_id' => $board['id'],
                'path' => 'img_' . $time
            ];
            Boards_image::create($input2);
        }
        if ($type === 'circles') return redirect()->route('board.page', [$detail, $category])->with('msg', '작성이 완료되었습니다.');
        else return redirect()->route('board.page', ['null', $category])->with('msg', '작성이 완료되었습니다.');
    }
    public function delete(Board $board){
        if (auth()->check()) {
            if ($board->user_id !== auth()->user()->id && auth()->user()->type !== 'admin') {
                return redirect(route('board.detail.view', $board->id))->with('msg', '관리자와 본인만 글 삭제 가능합니다.');
            }
        }
        $category = $board->category;
        $board->delete();
        return redirect(route('board.detail',[auth()->user()->circle_id,$category]))->with('msg','게시글이 삭제되었습니다.');
    }
    public function detailView($id)
    {
        $board = Board::where('id', $id)->first();
        if (auth()->user()->type === 'admin') {
            return view('board/boardDetailView', compact(['board']));
        }
        $view = Boards_view::where(['user_id' => auth()->user()->id, 'board_id' => $board->id]);
        if (!$view->exists()) {
            Boards_view::create([
                'user_id' => auth()->user()->id,
                'board_id' => $board->id,
                'views' => 1
            ]);
        }
        $board->update([
            'views' => Boards_view::where('board_id', $board->id)->count(),
        ]);
        return view('board/boardDetailView', compact(['board']));
    }

    public function likeClick(Request $request)
    {
        $board_like = Boards_like::where(['user_id' => $request->user_id, 'board_id' => $request->board_id]);
        if ($board_like->exists()) {
            if ($board_like->first()->like === 1) {
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
        Board::where('id', $request->board_id)->update([
            'like' => Boards_like::where(['like' => 1, 'board_id' => $request->board_id])->count()
        ]);

        return [
            'board' => Board::where('id', $request->board_id)->first(),
            'like' => $board_like->first(),
        ];
    }

    public function manage_index()
    {

    }
}
