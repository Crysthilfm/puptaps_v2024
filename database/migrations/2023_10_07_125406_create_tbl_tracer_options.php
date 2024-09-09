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
        Schema::create('tbl_tracer_options', function (Blueprint $table) {
            $table->increments('option_id');
            $table->unsignedInteger('question_id');
            $table->string('option_text');
            $table->foreign('question_id')
                ->references('question_id')
                ->on('tbl_tracer_questions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_tracer_options');
    }
};
