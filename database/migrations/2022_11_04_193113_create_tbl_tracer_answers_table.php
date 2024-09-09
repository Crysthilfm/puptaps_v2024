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
        Schema::create('tbl_tracer_answers', function (Blueprint $table) {
            $table->increments('answer_id');
            $table->unsignedInteger('alumni_id')
                  ->nullable();
            $table->foreign('alumni_id')
                  ->references('alumni_id')
                  ->on('tbl_alumni');
            $table->unsignedInteger('question_id')
                  ->nullable();
            $table->foreign('question_id')
                  ->references('question_id')
                  ->on('tbl_tracer_questions');
            $table->longText('answer');
            $table->foreign('tracer_version_id')
                  ->references('tracer_version_id')
                  ->on('tbl_tracer_form_version');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_tracer_answers');
    }
};
