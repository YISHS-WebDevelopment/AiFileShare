<?php

namespace App\Http\Controllers;

use App\Circle;
use App\File;
use App\Folder;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class FolderController extends Controller
{
    public function folderView($detail, $category, $url = null)
    {
        $circle_id = Circle::where('detail', $detail)->first()->id;
        if ($url) {
            $find = Folder::where('url', $url)->first();
            $allFolderAndFiles = Folder::where(['folder_id' => $find->id, 'circle_id' => $circle_id, 'category' => $category])->get()->mergeRecursive(File::where('folder_id', $find->id)->get());
            $parent = Folder::find($find->folder_id);
            $parent_arr = $this->parentPathArr($find, $detail, $category);
        } else {
            $allFolderAndFiles = Folder::where(['circle_id' => $circle_id, 'category' => $category])->whereNull('folder_id')->get()
                ->mergeRecursive(File::where(['circle_id' => $circle_id, 'category' => $category])->whereNull('folder_id')->get());
            $find = null;
            $parent = null;
            $parent_arr = null;
        }

        return view('folders/folderIndex', compact(['detail', 'category', 'url', 'find', 'allFolderAndFiles', 'parent', 'parent_arr']));
    }

    public function folderCreate(Request $request, $detail, $category, $url = null)
    {
        $circle_id = Circle::where('detail', $detail)->first()->id;

        if (!is_null($url)) {
            $find = Folder::where('url', $url)->first();
            foreach (Folder::where(['folder_id' => $find->id, 'circle_id' => $circle_id, 'category' => $category])->get() as $item) {
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
        } else {
            foreach (Folder::where(['folder_id' => null, 'circle_id' => $circle_id, 'category' => $category])->get() as $item) {
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
        foreach (File::where('folder_id', $id)->get() as $file) {
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

        $find->updated_at = date('Y-m-d', strtotime($find->updated_at));

        return $find;
    }

    public function folderZipDown($id)
    {
        $folder = Folder::find($id);
        if (!Storage::allDirectories($folder->path) && !Storage::allFiles($folder->path)) {
            return back()->with('msg', '빈 폴더는 다운 받을 수 없습니다.');
        }
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

    public function folderMove(Request $request)
    {
        $circle_id = Circle::where('detail', $request->detail)->first()->id;
        if ($request->url === 'null') {
            $find = false;
        } else {
            $find = Folder::where(['circle_id' => $circle_id, 'category' => $request->category, 'url' => $request->url])->first();
        }
        return $this->parentPathArr($find, $request->detail, $request->category);
    }

    public function folderMoveAction(Request $request)
    {
        $find = $request->type === 'folder' ? Folder::find($request->target) : File::find($request->target);

        if($request->id !== 'null')  {
            $targetFolder = Folder::find($request->id);
            $newPath = $targetFolder->path . "/" . $find->title;
            $folder_id = $request->id;
        } else {
            $newPath = 'circles/' . $find->circle->detail . "/" . $find->category . "/" . $find->title;
            $folder_id = null;
        }

        //find 가 update 되면 기존의 path 를 가져올 수 없으니 session 에다가 저장
        $request->session()->flash('savePath', $find->path);

        if($find->path === $newPath) return ['msg' => '같은 장소에는 옮길 수 없습니다.', 'state' => false];

        if ($request->type === 'folder') {
            foreach (Folder::where('folder_id', $folder_id)->get() as $item) {
                if ($item->title === $find->title) return ['msg' => '이 곳은 이미 같은 이름의 폴더가 있습니다.', 'state' => false];
            }
            //하위 폴더, 파일들 path 업데이트
            $this->pathUpdate($find->path, $newPath);

            $find->update([
                'folder_id' => $folder_id,
                'path' => $newPath,
                'updated_at' => now(),
            ]);
        } else {
            foreach (File::where('folder_id', $folder_id)->get() as $item) {
                if ($item->title === $find->title) return ['msg' => '이 곳은 이미 같은 이름의 파일이 있습니다.', 'state' => false];
            }
            $find->update([
                'folder_id' => $folder_id,
                'path' => $newPath
            ]);
        }

        Storage::move($request->session()->get('savePath'), $newPath);

        $this->whenCreateOrDelete();

        return ['msg' => '이동이 완료되었습니다.', 'state' => true];
    }

    public function pathUpdate($path, $newPath)
    {
        if (Storage::allDirectories($path)) {
            foreach (Storage::allDirectories($path) as $item) {
                $item_find = Folder::where('path', $item)->first();
                $item_find->update([
                    'path' => $newPath . "/" . $item_find->title
                ]);
            }
        }
        if (Storage::allFiles($path)) {
            foreach (Storage::allFiles($path) as $item) {
                $item_find = File::where('path', $item)->first();
                $item_find->update([
                    'path' => $newPath . "/" . $item_find->title
                ]);
            }
        }
    }
}
