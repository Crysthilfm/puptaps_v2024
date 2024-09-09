<?php

use App\Models\Forms\Pds\PdsQuestions;
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
        Schema::create('form_pds_questions', function (Blueprint $table) {
            $table->increments('question_id');
            $table->unsignedInteger('category_id')
                  ->nullable();
            $table->foreign('category_id')
                  ->references('category_id')
                  ->on('form_pds_categories');
            $table->string('question_text');
            $table->string('question_type');
            $table->string('question_placeholder')
                  ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_pds_questions');
    }
};
