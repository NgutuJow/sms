<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamPapersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('exam_papers', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('exam_id'); // Hii ilikuwa haipo
        $table->unsignedBigInteger('teacher_id');
        $table->unsignedBigInteger('class_id');
        $table->unsignedBigInteger('subject_id'); // Nimesahihisha spelling hapa
        $table->string('start_date');
        $table->string('end_date');
        $table->string('file_path')->nullable();
        $table->timestamps();

        // Foreign keys - Hakikisha data types zinalingana (unsignedBigInteger)
        $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
        $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
        $table->foreign('class_id')->references('id')->on('school_classes')->onDelete('cascade');
        $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exam_papers');
    }
}
