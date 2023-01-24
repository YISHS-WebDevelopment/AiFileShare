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
Route::middleware(['auth'])->group(function () {
    //동아리
    Route::get('/circles', [CirclesController::class, 'view'])->name('circles.page');
    Route::get('/circles/{detail}/{category}', [CirclesController::class, 'detail'])->name('circles.detail');

    //폴더
    Route::get('/circles/sharedFolder/{detail}/{category}/{url?}', [FolderController::class, 'folderView'])->name('folder.index');
    Route::post('/folderManager/folderCreate/{detail}/{category}/{url?}', [FolderController::class, 'folderCreate'])->name('folder.create');
    Route::get('/folder/delete/{id}', [FolderController::class, 'folderDelete'])->name('folder.delete');
    Route::post('/folder/rename', [FolderController::class, 'folderRename'])->name('folder.rename');
    Route::get('/folder/zipDown/{id}', [FolderController::class, 'folderZipDown'])->name('folder.zip.down');
    Route::post('/folder/move', [FolderController::class, 'folderMove'])->name('folder.move');
    Route::post('/folder/move/action', [FolderController::class, 'folderMoveAction'])->name('folder.move.action');

    //파일
    Route::post('/file/create/{detail}/{category}/{url?}', [FileController::class, 'fileCreate'])->name('file.create');
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
    Route::get('/post/modify/{id}', [BoardController::class, 'modifyPage'])->name('boardModifyPage');
    Route::put('/post/modify/{board}', [BoardController::class, 'modifyAction'])->name('boardModify.action');
    Route::post('/board/like',[BoardController::class, 'likeClick'])->name('board.like');
    Route::get('/post/delete/{board}',[BoardController::class, 'delete'])->name('board.delete');
    //댓글
    Route::post('/comment/write/{board}/{user}',[\App\Http\Controllers\CommentController::class, 'commentWrite'])->name('comment.write');
    Route::post('/comment/modify/{user}',[\App\Http\Controllers\CommentController::class, 'commentModify'])->name('comment.modify');
    Route::get('/comment/delete/{comment}',[\App\Http\Controllers\CommentController::class, 'commentDelete'])->name('comment.delete');

    //관리자
    Route::get('/userpage/management/index',[\App\Http\Controllers\UserController::class, 'index'])->name('user.index');

    //마이페이지
    Route::get('/mypage/{user}',[\App\Http\Controllers\MypageController::class, 'index'])->name('mypage.index');
    Route::put('/mypage/update/{user}',[\App\Http\Controllers\MypageController::class, 'update'])->name('user.update');
    Route::delete('/user/delete/{user}',[\App\Http\Controllers\UserController::class, 'delete'])->name('user.delete');
});

