<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectAssignmentsTable extends Migration
{
    public function up()
    {
        Schema::create('subject_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('subject_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('subject_assignments');
    }
}