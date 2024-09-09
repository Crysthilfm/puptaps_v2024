<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('user_id');

            $table->unsignedInteger('alumni_id')
                  ->nullable();
            $table->foreign('alumni_id')
                  ->references('alumni_id')
                  ->on('tbl_alumni');

            $table->unsignedInteger('admin_id')
                  ->nullable();
            $table->foreign('admin_id')
                  ->references('admin_id')
                  ->on('tbl_admin');

            $table->string('email');
            $table->string('username');
            $table->string('password');
            $table->string('user_role');
            $table->string('account_status');
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
};
