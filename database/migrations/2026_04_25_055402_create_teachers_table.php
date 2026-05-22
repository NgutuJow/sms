<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('teachers', function (Blueprint $table) {
    $table->id();
    $table->foreignId('branch_id')->constrained()->onDelete('cascade');
    $table->string('teacher_id_number')->unique(); // Staff ID
    $table->string('full_name');
    $table->string('email')->nullable();
    $table->string('phone');
    $table->enum('gender', ['Male', 'Female']);
    $table->date('dob')->nullable();
    $table->string('designation'); // e.g., Head of Dept, Subject Teacher
    $table->string('qualification'); // e.g., Degree, Diploma
    $table->date('joining_date');
    $table->boolean('status')->default(1);
    $table->string('image')->nullable();
    $table->text('address')->nullable();
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
        Schema::dropIfExists('teachers');
    }
}
