<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSemistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('semesters', function (Blueprint $table) {
    $table->id();
    $table->foreignId('academic_session_id')->constrained()->onDelete('cascade');
    $table->string('semester_name'); // Semester 1
    $table->date('start_date')->nullable();
    $table->date('end_date')->nullable();
    $table->boolean('is_current')->default(0);
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
        Schema::dropIfExists('semesters');
    }
}
