<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
    $table->id();

    $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // guardian

    $table->string('admission_no')->unique();
    $table->string('first_name');
    $table->string('middle_name')->nullable();
    $table->string('last_name');

    $table->date('dob');
    $table->enum('gender', ['Male', 'Female']);

    $table->string('region');
    $table->string('district');
    $table->string('street')->nullable();
    $table->string('address')->nullable();

    // Guardian info
    $table->string('guardian_name');
    $table->string('guardian_email');
    $table->string('guardian_phone')->nullable();
    $table->string('guardian_type')->nullable();
    $table->string('guardian_occupation')->nullable();
    $table->string('guardian_region')->nullable();
    $table->string('guardian_district')->nullable();
    $table->string('guardian_street')->nullable();
    $table->string('guardian_address')->nullable();

    // Academic
    $table->string('education_level')->nullable();

    $table->unsignedBigInteger('classes')->nullable();
    $table->unsignedBigInteger('stream')->nullable();
    $table->unsignedBigInteger('academic_session')->nullable();
    $table->unsignedBigInteger('semester')->nullable();
    $table->unsignedBigInteger('branches')->nullable();

    $table->string('school_attended')->nullable();
    $table->string('grade_completed')->nullable();

    $table->enum('suspended_before', ['Yes', 'No'])->nullable();
    $table->text('suspension_reason')->nullable();

    // Extra
    $table->string('status')->default('active');

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
        Schema::dropIfExists('students');
    }
}
