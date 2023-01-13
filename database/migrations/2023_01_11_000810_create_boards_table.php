<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boards', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('circle_check');
            $table->string('category');
            $table->string('title');
            $table->text('contents');
            $table->string('path')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->bigInteger('views')->default(0);
            $table->bigInteger('like')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('board');
    }
}
