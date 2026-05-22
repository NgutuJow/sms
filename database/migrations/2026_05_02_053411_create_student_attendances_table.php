<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    // Hakikisha hapa pameandikwa "student_attendances" kama seeder inavyotaka
    Schema::create('student_attendances', function (Blueprint $table) {
        $table->id();
        $table->foreignId('school_id');
        $table->foreignId('academic_session_id');
        $table->foreignId('class_id');
        $table->foreignId('semester_id');
        $table->date('date');
        $table->string('status');
        $table->foreignId('student_id');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendaces');
    }
}
