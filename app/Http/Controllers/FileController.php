<?php

namespace App\Http\Controllers;

use App\Circle;
use App\File;
use App\Folder;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function fileCreate(Request $request, $detail, $category, $url = null)
    {
        $file_data = $_FILES['file'];
        $tmp = explode('.', $file_data['name']);
        $ext = end($tmp);
        $circle_id = Circle::where('detail', $detail)->first()->id;

        if ($url) {
            $find = Folder::where('url', $url)->first();
            $find_file = File::where('title', $file_data['name'])->where('folder_id', $find['id'])->first();
            if (!is_null($find_file)) {
                return back()->with('msg', '이미 존재하는 파일명입니다 파일명을 바꾸고 업로드 하시거나 기존 파일을 삭제해 주세요');
            } else {
                Storage::putFileAs($find->path, $request->file, $file_data['name']);

                File::create([
                    'user_id' => auth()->user()->id,
                    'circle_id' => $circle_id,
                    'category' => $category,
                    'title' => $file_data['name'],
                    'path' => $find->path . "/" . $file_data['name'],
                    'folder_id' => $find['id'],
                    'size' => $request->file->getSize(),
                    'extension' => $ext,
                ]);
                $this->folderSizeUpdate($find->id, $request->file->getSize(), 'create');
                $this->whenCreateOrDelete();
                return back();
            }
        } else {
            if (File::where(['title' => $file_data['name'], 'folder_id' => null])->exists()) return back()->with('msg', '이미 존재하는 파일입니다 파일명을 바꾸고 업로드 하시거나 기존 파일을 삭제해 주세요');
            else {
                Storage::putFileAs('circles/' . $detail . "/" . $category, $request->file, $file_data['name']);

                File::create([
                    'user_id' => auth()->user()->id,
                    'circle_id' => $circle_id,
                    'category' => $category,
                    'title' => $file_data['name'],
                    'path' => 'circles/' . $detail . "/" . $category . "/" . $file_data['name'],
                    'size' => $request->file->getSize(),
                    'extension' => $ext
                ]);
                return back();
            }
        }
    }

    public function fileDownload($id)
    {
        $file = File::find($id);
        $headers = ['Content-Type: application/txt'];
        return Storage::download($file->path, $file->title);
    }

    public function fileDelete($id)
    {
        $file = File::find($id);
        $this->folderSizeUpdate($file->folder_id, $file->size, 'delete');
        $file->delete();
        Storage::delete($file->path);
        $this->whenCreateOrDelete();
        return back()->with('msg', '삭제되었습니다.');
    }
}
