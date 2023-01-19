<?php

namespace App\Http\Controllers;

use App\Circle;
use App\File;
use App\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class FolderController extends Controller
{
    public function index(Hash $hash)
    {
        return view('index', compact(['hash']));
    }

    public function folderCreate(Request $request, $detail, $category, $folder = null)
    {
        if (!is_null($folder)) {
            $find = Folder::where('url', $folder)->first();
            foreach (Folder::where('folder_id', $find->id)->get() as $item) {
                if ($request->title === $item->title) return back()->with('msg', '중복되는 폴더 이름이 있습니다.');
            }
            $cfolder = Folder::create([
                'title' => $request->title,
                'user_id' => auth()->user()->id,
                'circle_id' => Circle::where('detail', $detail)->first()->id,
                'url' => str_replace('/', '', Hash::make($request->title)),
                'folder_id' => $find['id'],
                'category' => $category,
                'path' => $find->path . "/" . $request->title,
                'created_at' => now(),
            ]);
            Storage::makeDirectory($find->path . "/" . $request->title);

            $this->rootIdUpdate(Folder::where('url', $cfolder->url)->first(), $cfolder);
        } else {
            foreach (Folder::where('folder_id', null)->get() as $item) {
                if ($request->title === $item->title) return back()->with('msg', '중복되는 폴더 이름이 있습니다.');
            }
            $cfolder = Folder::create([
                'title' => $request->title,
                'user_id' => auth()->user()->id,
                'circle_id' => Circle::where('detail', $detail)->first()->id,
                'url' => str_replace('/', '', Hash::make($request->title)),
                'category' => $category,
                'path' => 'circles/' . $detail . "/" . $category . "/" . $request->title,
                'created_at' => now(),
            ]);
            Storage::makeDirectory('circles/' . $detail . "/" . $category . "/" . $request->title);
        }

        return back();
    }

    public function folderDelete($id)
    {
        $find = Folder::find($id);
        foreach (\App\File::where('folder_id', $id)->get() as $file) {
            $file->delete();
        }
        foreach (Folder::where('folder_id', $id)->get() as $folder) {
            $folder->delete();
        }

        Storage::deleteDirectory($find->path);
        $find->delete();
        $this->whenCreateOrDelete();

        return back()->with('msg', '삭제되었습니다.');
    }

    public function rootIdUpdate($dir, $updateDir)
    {
        $parent_id = $dir->folder_id;
        if (is_null($parent_id)) {
            return $updateDir->update(['root_id' => $dir->id]);
        } else {
            $this->rootIdUpdate(Folder::find($parent_id), $updateDir);
        }
    }

    public function folderRename(Request $request)
    {
        $find = Folder::find($request->id);

        foreach (Folder::where('folder_id', $find->folder_id)->get() as $folder) {
            if ($folder->title === $request->title) return false;
        }
        $explode = explode('/', $find->path);
        $folderPathIdx = array_search(explode('/', $find->path)[count($explode) - 1], $explode);
        $request->session()->flash('prevPath', $find->path);

        //파일경로에 폴더 이름 바뀐거 업데이트
        foreach (Storage::allFiles($find->path) as $item) {
            $file = File::where('path', $item)->first();
            $filePathArr = explode("/", $item);
            $filePathArr[$folderPathIdx] = $request->title;
            $filePath = join("/", $filePathArr);

            $file->update([
                'path' => $filePath
            ]);
        }

        //폴더이름 바뀐거 해당 폴더의 하위폴더들에 업데이트
        foreach (Storage::allDirectories($find->path) as $item) {
            $folder = Folder::where('path', $item)->first();
            $folderPathArr = explode("/", $item);
            $folderPathArr[$folderPathIdx] = $request->title;
            $path = join("/", $folderPathArr);

            $folder->update([
                'path' => $path,
            ]);
        }

        $explode[count($explode) - 1] = $request->title;
        $folderPath = join("/", $explode);

        //폴더 이름 바꾸기
        $find->update([
            'title' => $request->title,
            'path' => $folderPath,
            'updated_at' => now(),
        ]);

        Storage::move($request->session()->get('prevPath'), $folderPath);

        return $find;
    }

    public function folderZipDown($detail, $category, $id)
    {
        $folder = Folder::find($id);
        $filePath = storage_path('app/');
        $zip = new \ZipArchive;

        // zip 아카이브 생성하기 위한 고유값
        $fileName = time() . '.zip';

        // zip 아카이브 생성 여부 확인
        if (!$zip->open($fileName, \ZipArchive::CREATE)) {
            exit("error");
        }

        // addFile ( 파일이 존재하는 경로, 저장될 이름 )
        foreach (Storage::allFiles($folder->path) as $item) {
            $copyPath = explode("/", $item);
            $findFolderIdx = array_search($folder->title, $copyPath);
            array_splice($copyPath, 0, $findFolderIdx);
            $copyPath = join("/", $copyPath);

            $zip->addFile($filePath . $item, $copyPath);
        }

        // 아카이브 닫아주기
        $zip->close();

        // 다운로드 될 zip 파일명
        $downZipName = $folder->title . '.zip';

        // 생성한 zip 파일을 다운로드하기
        header("Content-type: application/zip");
        header("Content-Disposition: attachment; filename=$downZipName");
        readfile($fileName);
        unlink($fileName);
    }


    public function folderView($detail, $category, $folder = null)
    {
        $pathArr = $this->getCurFolderPath(Folder::where('url',$folder)->first()->id, []);
        if (!is_null($folder)) {
            $find = Folder::where('url', $folder)->first();
            $files = $find->files->all();
            $parent = Folder::where('id', $find['folder_id'])->first();
            $parent_arr = [];
            $path = null;
            $cnt = 0;
            while (true) {
                if ($cnt === 0) {
                    $p = Folder::where('id', $find['folder_id'])->first();
                    if (!is_null($p)) {
                        array_push($parent_arr, $p);
                    }
                }
                if (is_null($p)) {
                    break;
                } else {
                    $p = Folder::where('id', $p['folder_id'])->first();
                    if (!is_null($p)) {
                        array_push($parent_arr, $p);
                    }
                }
                $cnt++;
            }
            if (!is_null($parent_arr)) {
                $parent_arr = array_reverse($parent_arr);
                $path = '';
                foreach ($parent_arr as $pathItem) {
                    $path .= '/' . $pathItem['title'];
                }
            }
        }
        return view('folders/folderIndex', compact(['find', 'detail', 'category', 'files', 'parent', 'path', 'pathArr']));
    }
}
