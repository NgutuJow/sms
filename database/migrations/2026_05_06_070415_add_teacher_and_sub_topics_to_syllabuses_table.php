<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTeacherAndSubTopicsToSyllabusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
 public function up()
{
    Schema::table('syllabuses', function (Blueprint $table) {
        if (!Schema::hasColumn('syllabuses', 'teacher_id')) {
            $table->unsignedBigInteger('teacher_id')->nullable()->after('topic_name');
        }
        
        // Badilisha .json kwenda .text au .longText
        if (!Schema::hasColumn('syllabuses', 'sub_topics')) {
            $table->longText('sub_topics')->nullable()->after('teacher_id');
        }
    });
}

public function down(): void
{
    Schema::table('syllabuses', function (Blueprint $table) {
        $table->dropColumn(['teacher_id', 'sub_topics', 'completion_date']);
    });
}
}
