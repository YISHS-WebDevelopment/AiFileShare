<?php

namespace App\Http\Controllers;

use App\File;
use App\Folder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    //현재 폴더의 path 를 배열로 쪼개서 return 해주는 함수
    public function getCurFolderPath($id , $result)
    {
        $folder = Folder::find($id);
        if(is_null($folder->folder_id)) {
            return $result;
        } else {
            $result[] = $id;
            $this->getCurFolderPath($folder->folder_id, $result);
        }

    }

    //파일을 생성&삭제 할 때 상위 폴더의 사이즈를 계산해주는 함수
    public function folderSizeUpdate($id, $size, $type)
    {
        $findFolder = Folder::find($id);
        if ($type === 'create') {
            $findFolder->update([
                'size' => $size + $findFolder->size,
            ]);
        } else {
            $findFolder->update([
                'size' => abs($findFolder->size - $size),
            ]);
        }
    }

    //파일을 생성&삭제/폴더를 삭제 할 때 루트 폴더와 루트 하위 폴더와 파일들의 사이즈들을 업데이트 해주는 함수
    public function whenCreateOrDelete()
    {
        $rootFolder = Folder::where('folder_id', null)->get();
        foreach ($rootFolder as $folder) {
            $folderPath = Storage::allDirectories($folder->path);
            $this->allFolderSizeUpdate($folderPath);

            //루트폴더의 사이즈를 계산하여 업데이트
            $rootSizeSum = 0;
            foreach (Folder::where('folder_id', $folder->id)->get() as $item) {
                $rootSizeSum += $item->size;
            }
            foreach (File::where('folder_id', $folder->id)->get() as $file) {
                $rootSizeSum += $file->size;
            }
            $folder->update([
                'size' => $rootSizeSum,
            ]);
        }
    }

    //루트 폴더의 하위폴더들의 사이즈를 계산하여 업데이트 해주는 함수
    public function allFolderSizeUpdate($path)
    {
        foreach ($path as $item) {
            $find = Folder::where('path', $item)->first();
            if ($find) {
                $files = File::where('folder_id', $find->id);
                $folders = Folder::where('folder_id', $find->id);
                $sizeSum = 0;
                foreach ($files->get() as $file) {
                    $sizeSum += $file->size;
                }
                foreach ($folders->get() as $folder) {
                    $sizeSum += $folder->size;
                }
                $find->update([
                    'size' => $sizeSum,
                ]);
            }
        }
    }
}
