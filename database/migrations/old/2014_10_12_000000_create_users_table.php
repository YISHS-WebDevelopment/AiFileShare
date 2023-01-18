<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
//            $table->foreignId('circle_id')->constrained();
            $table->string('auth_id');
            $table->string('password');
            $table->string('username');
<<<<<<< HEAD:database/migrations/2014_10_12_000000_create_users_table.php
            $table->string('circle_id')->nullable();
            $table->string('grade_id')->nullable();
            $table->string('student_id')->nullable();
=======
            $table->integer('grade');
            $table->string('student_id');
>>>>>>> master:database/migrations/old/2014_10_12_000000_create_users_table.php
            $table->string('type')->default('user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
