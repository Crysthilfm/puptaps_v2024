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
        Schema::create('form_eif_answers', function (Blueprint $table) {
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
                  ->on('form_eif_questions');
            $table->longText('answer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_eif_answers');
    }
};
