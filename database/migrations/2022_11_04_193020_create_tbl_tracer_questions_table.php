<?php

use App\Models\Tracer\TracerQuestions;
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
        Schema::create('tbl_tracer_questions', function (Blueprint $table) {
            $table->increments('question_id');
            $table->unsignedInteger('category_id')
                  ->nullable();
            $table->foreign('category_id')
                  ->references('category_id')
                  ->on('tbl_tracer_categories');
            $table->string('question_text');
            $table->string('question_type');
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
        Schema::dropIfExists('tbl_tracer_questions');
    }
};
