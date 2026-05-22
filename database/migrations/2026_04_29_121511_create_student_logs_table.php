<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_logs', function (Blueprint $table) {
             $table->id();
            $table->string('admission_no');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name');
            $table->string('dob');
            $table->string('gender');
            $table->string('region');
            $table->string('district');
            $table->string('street');
            $table->string('address');
            $table->string('guardian_name');
            $table->string('guardian_email');
            $table->string('guardian_phone');
            $table->string('guardian_occupation');
            $table->string('guardian_address');
            $table->string('guardian_type');
            $table->string('guardian_region');
            $table->string('guardian_street');
            $table->string('guardian_district');
            $table->string('education_level');
            $table->string('classes');
            $table->string('stream');
            $table->string('school_attended');
            $table->string('grade_completed');
            $table->string('suspended_before');
            $table->string('suspension_reason');
            $table->string('academic_session');
            $table->string('semester');
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
        Schema::dropIfExists('student_logs');
    }
}
