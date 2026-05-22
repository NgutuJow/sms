<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateExamsTableAddFields extends Migration
{
    public function up()
    {
        Schema::table('exams', function (Blueprint $table) {
            if (!Schema::hasColumn('exams', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
            if (!Schema::hasColumn('exams', 'exam_type')) {
                $table->string('exam_type')->default('MIDTERM')->after('description');
            }
            if (!Schema::hasColumn('exams', 'start_date')) {
                $table->timestamp('start_date')->nullable()->after('exam_type');
            }
            if (!Schema::hasColumn('exams', 'end_date')) {
                $table->timestamp('end_date')->nullable()->after('start_date');
            }
            if (!Schema::hasColumn('exams', 'total_marks')) {
                $table->integer('total_marks')->default(100)->after('end_date');
            }
            if (!Schema::hasColumn('exams', 'passing_marks')) {
                $table->integer('passing_marks')->default(40)->after('total_marks');
            }
            if (!Schema::hasColumn('exams', 'created_by')) {
                $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->after('passing_marks');
            }
            if (!Schema::hasColumn('exams', 'status')) {
                $table->string('status')->default('DRAFT')->after('created_by');
            }
        });
    }

    public function down()
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn(['description', 'exam_type', 'start_date', 'end_date', 'total_marks', 'passing_marks', 'created_by', 'status']);
        });
    }
}
