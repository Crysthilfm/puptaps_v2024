<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_announcement', function (Blueprint $table) {
            $table->increments("announcement_id");
            $table->string("announcement_image");
            $table->string("announcement_title");
            $table->longText("announcement_text");
            $table->timestamp("created_at");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_announcement');
    }
};
