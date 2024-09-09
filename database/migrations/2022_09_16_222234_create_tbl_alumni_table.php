<?php

use App\Models\Alumni;
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
        Schema::create('tbl_alumni', function (Blueprint $table) {
            $table->increments('alumni_id');
            $table->string('stud_number');
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')
                  ->nullable();
            $table->string('suffix')
                  ->nullable();
            $table->string('course_id');
            $table->integer('batch');
            $table->date('year_graduated');
            $table->string('sex')
                  ->nullable();
            $table->date('birthday')
                  ->nullable();
            $table->integer('age')
                  ->nullable();
            $table->string('religion')
                  ->nullable();
            $table->string('civil_status')
                  ->nullable();
            $table->string('city_address')
                  ->nullable();
            $table->string('provincial_address')
                  ->nullable();
            $table->string('email');
            $table->string('number');
            $table->string('user_pfp')
                  ->nullable();
            $table->string('profile_status');
            $table->date('tracer_updated_at')
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
        Schema::dropIfExists('tbl_alumni');
    }
};
