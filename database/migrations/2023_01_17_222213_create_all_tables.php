<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('circles', function (Blueprint $table) {
            $table->id();
            $table->string('detail');
            $table->string('name');
        });
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('circle_id')->constrained();
            $table->string('auth_id');
            $table->string('introduce')->nullable()->default('소개글이 없습니다.');
            $table->string('profile')->nullable()->default('public/profile_img/default_profile.png');
            $table->string('password');
            $table->string('username');
            $table->integer('grade');
            $table->string('student_id');
            $table->string('type')->default('user');
        });
        Schema::create('admins', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('password');
            $table->string('name');
        });
        Schema::create('folders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('circle_id')->constrained();
            $table->string('title');
            $table->integer('folder_id')->nullable();
            $table->integer('root_id')->nullable();
            $table->string('category')->nullable();
            $table->string('url')->nullable();
            $table->bigInteger('size')->default(0);
            $table->string('path');
            $table->timestamps();
        });
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('folder_id')->constrained();
            $table->string('title');
            $table->string('path');
            $table->bigInteger('size');
            $table->string('extension');
            $table->dateTime('created_at')->useCurrent();
        });
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->integer('grade');
            $table->string('group');
            $table->string('number');
        });
        Schema::create('boards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('circle_check');
            $table->string('category');
            $table->string('title');
            $table->longText('contents');
            $table->string('path')->nullable();
            $table->bigInteger('views')->default(0);
            $table->bigInteger('like')->default(0);
            $table->timestamp('created_at')->useCurrent();
        });
        Schema::create('boards_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('board_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->boolean('views')->default(0);
        });
        Schema::create('boards_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('board_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->boolean('like')->default(0);
        });
        Schema::create('dirs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('dir');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('all_tables');
    }
}
