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
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class initController extends Controller
{
    public function init()
    {
        Schema::disableForeignKeyConstraints();

        DB::table('users')->truncate();
        DB::table('folders')->truncate();
        DB::table('grades')->truncate();
        DB::table('files')->truncate();
        DB::table('circles')->truncate();
        DB::table('boards')->truncate();
        DB::table('admins')->truncate();
        DB::table('boards_views')->truncate();
        DB::table('boards_likes')->truncate();
        DB::table('boards_images')->truncate();
        DB::table('dirs')->truncate();

        Circle::create([
            'detail' => 'all',
            'name' => '전체'
        ]);
        Circle::create([
            'detail' => 'WebDesign',
            'name' => '웹디자인'
        ]);
        Circle::create([
            'detail' => 'GraphicDesign',
            'name' => '그래픽디자인'
        ]);
        Circle::create([
            'detail' => '3D',
            'name' => '3D'
        ]);
        Circle::create([
            'detail' => 'CAD',
            'name' => '캐드'
        ]);

        User::create([
            'auth_id' => 'thsgusqls',
            'password' => '1234',
            'username' => '손현빈',
            'circle_id' => 2,
            'grade' => 3,
            'student_id' => '30502'
        ]);

        User::create([
            'auth_id' => 'gkguswns',
            'password' => '1234',
            'username' => '하현준',
            'circle_id' => 2,
            'grade' => '3',
            'student_id' => '30503'
        ]);

        User::create([
            'auth_id' => 'whtjdals',
            'password' => '1234',
            'username' => '조성민',
            'circle_id' => 4,
            'grade' => '2',
            'student_id' => '20502'
        ]);

        User::create([
            'auth_id' => 'rlaqjatn',
            'password' => '1234',
            'username' => '김범수',
            'circle_id' => 4,
            'grade' => '2',
            'student_id' => '20503'
        ]);

        User::create([
            'auth_id' => 'qkrtldms',
            'password' => '1234',
            'username' => '박시은',
            'circle_id' => 3,
            'grade' => '3',
            'student_id' => '30504'
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
        Storage::deleteDirectory('circles');
        foreach(Circle::all() as $circle) {
            if($circle->detail !== 'all') {
                Storage::makeDirectory('circles/'.$circle->detail.'/all');
                Storage::makeDirectory('circles/'.$circle->detail.'/1');
                Storage::makeDirectory('circles/'.$circle->detail.'/2');
                Storage::makeDirectory('circles/'.$circle->detail.'/3');
                Dir::create([
                    'name' => $circle->detail.'/all',
                    'dir' => 'circles/'.$circle->detail."/".'all',
                ]);
                Dir::create([
                    'name' => $circle->detail.'/1',
                    'dir' => 'circles/'.$circle->detail."/".'1',
                ]);
                Dir::create([
                    'name' => $circle->detail.'/2',
                    'dir' => 'circles/'.$circle->detail."/".'2',
                ]);
                Dir::create([
                    'name' => $circle->detail.'/3',
                    'dir' => 'circles/'.$circle->detail."/".'3',
                ]);
            }
        }

        Schema::enableForeignKeyConstraints();
    }
}
