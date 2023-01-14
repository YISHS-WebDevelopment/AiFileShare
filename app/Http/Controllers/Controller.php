<?php

namespace App\Http\Controllers;

use App\File;
use App\Folder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    //파일을 생성&삭제할 때 파일이 생성된 곳부터 루트 폴더까지 거쳐가는 모든 폴더의 사이즈를 계산하여 업데이트 해주는 함수
    //좀 더럽게 짬 시간 날 때 바꿔주세용 ^^;
    public function sumSizeUpdate($dir, $sum)
    {
        if (isset($dir)) {
            $parent_id = $dir->id;
            if (!$dir->folder_id) {
                foreach (File::where('folder_id', $parent_id)->get() as $file) {
                    $sum += $file->size;
                }
                if (Folder::where('folder_id', $parent_id)->exists()) {
                    foreach (Folder::where('folder_id', $parent_id)->get() as $folder) {
                        $sum += $folder->size;
                    }
                }
                return Folder::find($parent_id)->update([
                    'size' => $sum
                ]);
            } else {
                foreach (File::where('folder_id', $parent_id)->get() as $file) {
                    $sum += $file->size;
                }
                if (Folder::where('folder_id', $parent_id)->exists()) {
                    foreach (Folder::where('folder_id', $parent_id)->get() as $folder) {
                        $sum += $folder->size;
                    }
                }
                Folder::find($parent_id)->update([
                    'size' => $sum
                ]);
                $this->sumSizeUpdate(Folder::find($dir->folder_id), 0);
            }
        }
    }
}
