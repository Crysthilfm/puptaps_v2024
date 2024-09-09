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
        Schema::create('reminder_recipients', function (Blueprint $table) {
            $table->increments('rr_id');
            $table->string('recipientEmail');
            $table->unsignedInteger('rh_id')
                  ->nullable();
            $table->foreign('rh_id')
                ->references('rh_id')
                ->on('reminder_histories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reminder_recipients');
    }
};
