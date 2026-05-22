<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();

            // SIMPLE SAFE FK USING id ONLY
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('from_class');
            $table->unsignedBigInteger('to_class');
            $table->unsignedBigInteger('promoted_by');

            $table->string('academic_year');
            $table->text('remarks')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('from_class')->references('id')->on('school_classes');
            $table->foreign('to_class')->references('id')->on('school_classes');
            $table->foreign('promoted_by')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('promotions');
    }
};