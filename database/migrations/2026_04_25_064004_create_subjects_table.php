<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subjects', function (Blueprint $table) {
    $table->id();
    $table->foreignId('school_class_id')->constrained('school_classes')->onDelete('cascade');
    $table->string('subject_name'); // Mathematics, Physics
    $table->string('subject_code')->nullable(); // MATH-01
    $table->enum('type', ['Theory', 'Practical', 'Both'])->default('Theory');
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
        Schema::dropIfExists('subjects');
    }
}
