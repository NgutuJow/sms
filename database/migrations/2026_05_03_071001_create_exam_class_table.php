<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamClassTable extends Migration
{
    public function up()
    {
        Schema::create('exam_class', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('school_classes')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['exam_id', 'class_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_class');
    }
}
