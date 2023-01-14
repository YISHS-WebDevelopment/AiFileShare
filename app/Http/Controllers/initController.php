<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Circle;
use App\Dir;
use App\Grade;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class initController extends Controller
{
    public function init()
    {
        DB::table('users')->truncate();
        DB::table('folders')->truncate();
        DB::table('grades')->truncate();
        DB::table('files')->truncate();
        DB::table('circles')->truncate();
        DB::table('boards')->truncate();
        DB::table('admins')->truncate();
        DB::table('boards_views')->truncate();
        DB::table('boards_likes')->truncate();
        DB::table('dirs')->truncate();

        User::create([
            'id' => 'thsgusqls',
            'password' => '1234',
            'username' => '손현빈',
            'circle_id' => 'WebDesign',
            'grade_id' => '3',
            'student_id' => '30502'
        ]);

        User::create([
            'id' => 'gkguswns',
            'password' => '1234',
            'username' => '하현준',
            'circle_id' => 'WebDesign',
            'grade_id' => '3',
            'student_id' => '30503'
        ]);

        User::create([
            'id' => 'whtjdals',
            'password' => '1234',
            'username' => '조성민',
            'circle_id' => '3D',
            'grade_id' => '2',
            'student_id' => '20502'
        ]);

        User::create([
            'id' => 'rlaqjatn',
            'password' => '1234',
            'username' => '김범수',
            'circle_id' => '3D',
            'grade_id' => '2',
            'student_id' => '20503'
        ]);

        User::create([
            'id' => 'qkrtldms',
            'password' => '1234',
            'username' => '박시은',
            'circle_id' => 'GraphicDesign',
            'grade_id' => '3',
            'student_id' => '30504'
        ]);

        Circle::create([
            'circle' => 'WebDesign',
            'name' => '웹디자인'
        ]);
        Circle::create([
            'circle' => 'GraphicDesign',
            'name' => '그래픽디자인'
        ]);
        Circle::create([
            'circle' => '3D',
            'name' => '3D'
        ]);
        Circle::create([
            'circle' => 'CAD',
            'name' => '캐드'
        ]);

        Admin::create([
            'id' => 'admin',
            'password' => Hash::make('rhtlgustlstmdqlsrlawhddnjsshtmdwns'),
            'name' => '관리자다'
        ]);

        for ($i = 1; $i <= 3; $i++) {
            for ($j = 4; $j <= 5; $j++) {
                for ($k = 1; $k <= 21; $k++) {
                    Grade::create([
                        'grade' => $i,
                        'group' => $j === 4 ? '04' : '05',
                        'number' => $k < 10 ? '0'.$k : $k,
                    ]);
                }
            }
        }
        Dir::create([
            'name' => 'all/all',
            'dir' => 'all/all',
        ]);
        Dir::create([
            'name' => 'all/1',
            'dir' => 'all/1',
        ]);
        Dir::create([
            'name' => 'all/2',
            'dir' => 'all/2',
        ]);
        Dir::create([
            'name' => 'all/3',
            'dir' => 'all/3',
        ]);
        Storage::makeDirectory('all/all');
        Storage::makeDirectory('all/1');
        Storage::makeDirectory('all/2');
        Storage::makeDirectory('all/3');
        foreach(Circle::all() as $circle) {
            Storage::makeDirectory('circle/'.$circle->circle.'/all');
            Storage::makeDirectory('circle/'.$circle->circle.'/1');
            Storage::makeDirectory('circle/'.$circle->circle.'/2');
            Storage::makeDirectory('circle/'.$circle->circle.'/3');
            Dir::create([
                'name' => $circle->circle.'/all',
                'dir' => 'circle/'.$circle->circle."/".'all',
            ]);
            Dir::create([
                'name' => $circle->circle.'/1',
                'dir' => 'circle/'.$circle->circle."/".'1',
            ]);
            Dir::create([
                'name' => $circle->circle.'/2',
                'dir' => 'circle/'.$circle->circle."/".'2',
            ]);
            Dir::create([
                'name' => $circle->circle.'/3',
                'dir' => 'circle/'.$circle->circle."/".'3',
            ]);
        }
    }
}
