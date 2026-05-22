<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateExaminationsTableAddFields extends Migration
{
    public function up()
    {
        Schema::table('examinations', function (Blueprint $table) {
            if (!Schema::hasColumn('examinations', 'exam_id')) {
                $table->foreignId('exam_id')->nullable()->constrained('exams')->onDelete('cascade');
            }
            if (!Schema::hasColumn('examinations', 'class_id')) {
                $table->foreignId('class_id')->nullable()->constrained('school_classes')->onDelete('cascade');
            }
            if (!Schema::hasColumn('examinations', 'subject_id')) {
                $table->foreignId('subject_id')->nullable()->constrained('subjects')->onDelete('cascade');
            }
            if (!Schema::hasColumn('examinations', 'teacher_id')) {
                $table->foreignId('teacher_id')->nullable()->constrained('teachers')->onDelete('set null');
            }
            if (!Schema::hasColumn('examinations', 'question_count')) {
                $table->integer('question_count')->nullable();
            }
            if (!Schema::hasColumn('examinations', 'duration_minutes')) {
                $table->integer('duration_minutes')->nullable();
            }
            if (!Schema::hasColumn('examinations', 'instructions')) {
                $table->text('instructions')->nullable();
            }
            if (!Schema::hasColumn('examinations', 'is_published')) {
                $table->boolean('is_published')->default(false);
            }
            if (!Schema::hasColumn('examinations', 'published_date')) {
                $table->timestamp('published_date')->nullable();
            }
            if (!Schema::hasColumn('examinations', 'status')) {
                $table->string('status')->default('DRAFT');
            }
        });
    }

    public function down()
    {
        Schema::table('examinations', function (Blueprint $table) {
            if (Schema::hasColumn('examinations', 'exam_id')) {
                $table->dropForeign(['exam_id']);
            }
            if (Schema::hasColumn('examinations', 'class_id')) {
                $table->dropForeign(['class_id']);
            }
            if (Schema::hasColumn('examinations', 'subject_id')) {
                $table->dropForeign(['subject_id']);
            }
            if (Schema::hasColumn('examinations', 'teacher_id')) {
                $table->dropForeign(['teacher_id']);
            }
            $table->dropColumn(['exam_id', 'class_id', 'subject_id', 'teacher_id', 'question_count', 'duration_minutes', 'instructions', 'is_published', 'published_date', 'status']);
        });
    }
}
