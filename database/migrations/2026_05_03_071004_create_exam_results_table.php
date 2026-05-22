<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamResultsTable extends Migration
{
    public function up()
    {
        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('school_classes')->onDelete('cascade');
            $table->float('total_marks')->default(0);
            $table->float('average_marks')->default(0);
            $table->string('grade')->nullable();
            $table->integer('position')->nullable();
            $table->text('remarks')->nullable();
            $table->boolean('is_passed')->default(false);
            $table->timestamps();

            $table->unique(['exam_id', 'student_id']);
            $table->index(['exam_id', 'class_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_results');
    }
}
