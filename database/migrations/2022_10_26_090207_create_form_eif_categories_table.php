<?php

use App\Models\Forms\Eif\EifCategories;
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
        Schema::create('form_eif_categories', function (Blueprint $table) {
            $table->increments('category_id');
            $table->unsignedInteger('form_id')
                  ->nullable();
            $table->foreign('form_id')
                  ->references('form_id')
                  ->on('tbl_forms');
            $table->string('category_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_eif_categories');
    }
};
