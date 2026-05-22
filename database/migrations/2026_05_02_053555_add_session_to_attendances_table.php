<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSessionToAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Tumebadilisha kutoka 'attendances' kwenda 'student_attendances' herufi kwa herufi
        Schema::table('student_attendances', function (Blueprint $table) {
            $table->foreignId('academic_session_id')->after('class_id')
                  ->constrained()->cascadeOnDelete();

            $table->foreignId('semester_id')->nullable()->after('academic_session_id')
                  ->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_attendances', function (Blueprint $table) {
            // Ni vizuri kuweka njia ya kufuta hizi foreign keys na columns kama ukirun migrate:rollback
            $table->dropForeign(['academic_session_id']);
            $table->dropForeign(['semester_id']);
            
            $table->dropColumn(['academic_session_id', 'semester_id']);
        });
    }
}