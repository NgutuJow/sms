<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('student_logs', function (Blueprint $table) {
            $table->string('middle_name')->nullable()->change();
            $table->string('street')->nullable()->change();
            $table->string('address')->nullable()->change();
            $table->string('guardian_phone')->nullable()->change();
            $table->string('guardian_type')->nullable()->change();
            $table->string('guardian_occupation')->nullable()->change();
            $table->string('guardian_region')->nullable()->change();
            $table->string('guardian_district')->nullable()->change();
            $table->string('guardian_street')->nullable()->change();
            $table->string('guardian_address')->nullable()->change();
            $table->string('education_level')->nullable()->change();
            $table->string('stream')->nullable()->change();
            $table->string('semester')->nullable()->change();
            $table->string('school_attended')->nullable()->change();
            $table->string('grade_completed')->nullable()->change();
            $table->string('suspended_before')->nullable()->change();
            $table->string('suspension_reason')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('student_logs', function (Blueprint $table) {
            $table->string('middle_name')->nullable(false)->change();
            $table->string('street')->nullable(false)->change();
            $table->string('address')->nullable(false)->change();
            $table->string('guardian_phone')->nullable(false)->change();
            $table->string('guardian_type')->nullable(false)->change();
            $table->string('guardian_occupation')->nullable(false)->change();
            $table->string('guardian_region')->nullable(false)->change();
            $table->string('guardian_district')->nullable(false)->change();
            $table->string('guardian_street')->nullable(false)->change();
            $table->string('guardian_address')->nullable(false)->change();
            $table->string('education_level')->nullable(false)->change();
            $table->string('stream')->nullable(false)->change();
            $table->string('semester')->nullable(false)->change();
            $table->string('school_attended')->nullable(false)->change();
            $table->string('grade_completed')->nullable(false)->change();
            $table->string('suspended_before')->nullable(false)->change();
            $table->string('suspension_reason')->nullable(false)->change();
        });
    }
};
