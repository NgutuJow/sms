<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'classes')) return;

            $table->foreign('classes')->references('id')->on('school_classes')->cascadeOnDelete();
            $table->foreign('stream')->references('id')->on('streams')->nullOnDelete();
            $table->foreign('academic_session')->references('id')->on('academic_sessions')->cascadeOnDelete();
            $table->foreign('semester')->references('id')->on('semesters')->nullOnDelete();
            $table->foreign('branches')->references('id')->on('branches')->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['classes']);
            $table->dropForeign(['stream']);
            $table->dropForeign(['academic_session']);
            $table->dropForeign(['semester']);
            $table->dropForeign(['branches']);
        });
    }
};
