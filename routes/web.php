<?php

use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CirclesController;
use App\Http\Controllers\initController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\BoardController;

//db초기화
Route::get('/init', [initController::class, 'init']);

//메인
Route::get('/', [MainController::class, 'index'])->name('index');

//로그인 & 회원가입
Route::get('/auth/login', [AuthController::class, 'login'])->name('login.page');
Route::get('/auth/register', [AuthController::class, 'register'])->name('register.page');
Route::post('/auth/login', [AuthController::class, 'loginAction'])->name('login.action');
Route::post('/auth/register', [AuthController::class, 'registerAction'])->name('register.action');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

//관리자 로그인 & 로그아웃
Route::get('/admin/login',[\App\Http\Controllers\AdminController::class,'login'])->name('admin.login.page');
Route::post('/admin/login',[\App\Http\Controllers\AdminController::class,'loginAction'])->name('admin.login.action');

//로그인 된 유저만
Route::middleware('auth')->group(function () {
    //동아리
    Route::get('/circles', [CirclesController::class, 'view'])->name('circles.page');
    Route::get('/circles/{detail}/{category}', [CirclesController::class, 'detail'])->name('circles.detail');
    Route::get('/circles/{detail}/{category}/sharedFolder', [CirclesController::class, 'sharePage'])->name('circles.share');

    //폴더
    Route::get('/circles/{detail}/{category}/sharedFolder/{folder}', [FolderController::class, 'folderView'])->name('folder.index');
    Route::post('/folderManager/folderCreate/{detail}/{category}/{folder?}', [FolderController::class, 'folderCreate'])->name('folder.create');

    //파일
    Route::post('/file/create/{folder}', [FileController::class, 'fileCreate'])->name('file.create');
    Route::get('/file/download/{id}',[FileController::class, 'fileDownload'])->name('file.download');
    Route::get('/file/delete/{id}', [FileController::class, 'fileDelete'])->name('file.delete');

    //게시판
    Route::get('/board/{detail?}/{category}', [BoardController::class, 'view'])->name('board.page');
    Route::get('/board/{detail}/{category}',[BoardController::class, 'view'])->name('board.detail');
    Route::get('/board/{detail?}/{category}/{type}/write', [BoardController::class, 'writePage'])->name('board.all.write');
    Route::get('/board/{detail}/{category}/{type}/write', [BoardController::class, 'writePage'])->name('board.circles.write');
    Route::post('/board/{detail?}/{category}/{type}/write',[BoardController::class, 'writeAction'])->name('board.all.write.action');
    Route::post('/board/{detail}/{category}/{type}/write',[BoardController::class, 'writeAction'])->name('board.circles.write.action');
    Route::get('/board/{id}',[BoardController::class, 'detailView'])->name('board.detail.view');
    Route::post('/board/like',[BoardController::class, 'likeClick'])->name('board.like');
});
//관리자(어드민) 페이지
Route::middleware('AdminChk')->group(function () {
    Route::get('/admin/index',[\App\Http\Controllers\AdminController::class,'index'])->name('admin.index');
    Route::get('/admin/logout',[\App\Http\Controllers\AdminController::class,'logout'])->name('admin.logout');
    Route::get('/admin/folder/manage',[\App\Http\Controllers\FolderController::class,'manage_index'])->name('folder_manage.page');
    Route::get('/admin/board/manage',[\App\Http\Controllers\BoardController::class,'manage_index'])->name('board_manage.page');
    Route::get('/admin/folder/circle/{circle?}',[\App\Http\Controllers\FolderController::class,'folderList'])->name('folder_list.page');
    Route::put('/admin/folder/folderManagement',[\App\Http\Controllers\FolderController::class,'folderManagementPage'])->name('folder_management.page');
    Route::delete('/admin/folder/folderDel',[\App\Http\Controllers\FolderController::class,'folderDelete'])->name('folder.delete');

});
