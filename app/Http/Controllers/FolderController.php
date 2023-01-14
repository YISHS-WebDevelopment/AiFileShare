<?php

namespace App\Http\Controllers;

use App\Circle;
use App\Folder;
use App\Http\Controllers\Controller;
use Faker\Provider\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

            $cfolder = Folder::create([
                'title' => $request->title,
                'user_id' => auth()->user()->id,
                'circle_id' => $detail,
                'url' => str_replace('/', '', Hash::make($request->title)),
                'folder_id' => $find['id'],
                'grade_id' => $category,
            ]);

        $this->rootIdUpdate(Folder::where('url',$cfolder->url)->first(),$cfolder);

        } else {
            $cfolder = Folder::create([
                'title' => $request->title,
                'user_id' => auth()->user()->id,
                'circle_id' => $detail,
                'url' => str_replace('/', '', Hash::make($request->title)),
                'grade_id' => $category,
            ]);
        }
        //!!!
        // auth()->user()->id 로 꼭바꾸세요 개석섹스
        return back();
    }

    public function rootIdUpdate($dir, $updateDir) {
        $parent_id = $dir->folder_id;
        if(is_null($parent_id)) {
            return $updateDir->update(['root_id' => $dir->id]);
        } else {
            $this->rootIdUpdate(Folder::find($parent_id),$updateDir);
        }
    }

    public function folderView($detail, $category, $folder = null)
    {
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
        return view('folders/folderIndex', compact(['find', 'detail', 'category', 'files', 'parent', 'path']));
    }

    public function manage_index(Circle $circle)
    {
        return view('admin.management.folder_manage', compact(['circle']));
    }

    public function folderManagementPage()
    {
//        return view('admin.management.folder_list',compact(['folders']));

    }

//    public function folderDelete(Request $request)
//    {
//        $folder = Folder::find($request['id']);
//        $files = $folder->files;
//        $childFolder = $folder->childFolders($folder['url']);
//        if (!empty($childFolder)) {
//            foreach ($childFolder as $c) {
//                $c->delete();
//
//            }
//        }
//        if (!empty($files)) {
//            foreach ($files as $file) {
//                \Illuminate\Support\Facades\File::delete($file['title']);
//                }
//            $files->delete();
//
//        }
//        $folder->delete();
//
//    }

}
