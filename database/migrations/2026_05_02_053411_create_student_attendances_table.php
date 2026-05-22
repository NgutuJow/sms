<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Hapa hakikisha inasomeka "CreateStudentAttendancesTable" badala ya CreateAttendacesTable
class CreateStudentAttendancesTable extends Migration
{
    public function up()
    {
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

    public function down()
    {
        Schema::dropIfExists('student_attendances');
    }
}