<?php

namespace App\Http\Controllers;

use App\File;
use App\Folder;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function fileCreate(Request $request, $folder)
    {
        $find = Folder::where('url', $folder)->first();
        $file_data = $_FILES['file'];
        $find_file = File::where('title', $file_data['name'])->where('folder_id', $find['id'])->first();
        if (!is_null($find_file)) {
            return back()->with('msg', '이미 존재하는 파일입니다 파일명을 바꾸고 업로드 하시거나 기존 파일을 삭제해 주세요');
        } else {
            $tmp = explode('.', $file_data['name']);
            $ext = $tmp[count($tmp) - 1];
            $img = ['jpg', 'jpeg', 'jfif', 'png', 'svg', 'gif'];
            if (in_array($ext, $img)) {
                $file = $request->file->storeAs('/public/' . $find['circle_id'] . '/img', time() . '_' . $file_data['name']);
            } else {
                $file = $request->file->storeAs('/public/' . $find['circle_id'] . '/files', time() . '_' . $file_data['name']);
            }
            File::create([
                'user_id' => auth()->user()->id,
                'title' => $file_data['name'],
                'path' => $file,
                'folder_id' => $find['id']
            ]);
            return back();
        }
    }

    public function fileDownload($id)
    {
        $file = File::find($id);
        $path = storage_path('app/') . $file->path;
        $headers = ['Content-Type: application/txt'];
        $replace = str_replace('/', '\\', $path);
        return Response::download($replace, $file->title);
    }

    public function fileDelete($id)
    {
        $file = File::find($id);
        $file->delete();

        return back()->with('msg', '삭제되었습니다.');
    }
}