<?php

namespace App\Http\Controllers;

use App\Circle;
use App\File;
use App\Folder;
use App\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function parentPathArr($find, $detail, $category)
    {
        if($find) {
            $parent_arr = [];
            $cnt = 0;
            while (true) {
                if ($cnt === 0) {
                    $dir = Folder::where('id', $find->folder_id)->first();
                    if (!is_null($dir)) $parent_arr[] = $dir;
                }
                if (is_null($dir)) {
                    break;
                } else {
                    $dir = Folder::where('id', $dir->folder_id)->first();
                    if (!is_null($dir)) $parent_arr[] = $dir;
                }
                $cnt++;
            }
            $parent_arr = array_reverse($parent_arr);
            $parent_arr[] = $find;
            $html = Folder::where('folder_id', $find->id)->get()->mergeRecursive(File::where('folder_id', $find->id)->get());

            $result = [
                'path' => $parent_arr,
                'current' => $find,
                'parent' => Folder::find($find->folder_id),
                'html' => $html->all(),
            ];
        } else {
            $circle_id = Circle::where('detail', $detail)->first()->id;

            $html = Folder::where(['circle_id' => $circle_id, 'category' => $category])->whereNull('folder_id')->get()->
                    mergeRecursive(File::where(['circle_id' => $circle_id, 'category' => $category])->whereNull('folder_id')->get());

            $result = [
                'path' => null,
                'current' => null,
                'parent' => null,
                'html' => $html,
            ];
        }

        foreach($result['html'] as $item) {
            $item['user'] = $item->user;
            $item->created_at = date('Y-m-d', strtotime($item->created_at));
            if($item->updated_at) $item->updated_at = date('Y-m-d', strtotime($item->updated_at));
        }

        return $result;
    }

    //????????? ??????&?????? ??? ??? ?????? ????????? ???????????? ??????????????? ??????
    public function folderSizeUpdate($id, $size, $type)
    {
        $findFolder = Folder::find($id);
        if(!is_null($id)) {
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
    }

    //????????? ??????&??????/????????? ?????? ??? ??? ?????? ????????? ?????? ?????? ????????? ???????????? ??????????????? ???????????? ????????? ??????
    public function whenCreateOrDelete()
    {
        $rootFolder = Folder::where('folder_id', null)->get();
        foreach ($rootFolder as $folder) {
            $folderPath = Storage::allDirectories($folder->path);
            $this->allFolderSizeUpdate($folderPath);

            //??????????????? ???????????? ???????????? ????????????
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

    //?????? ????????? ?????????????????? ???????????? ???????????? ???????????? ????????? ??????
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
