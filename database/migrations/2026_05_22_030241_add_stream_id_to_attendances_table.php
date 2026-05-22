<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStreamIdToAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Tumebadilisha kutoka 'attendances' kwenda 'student_attendances'
        Schema::table('student_attendances', function (Blueprint $table) {
            $table->foreignId('stream_id')->nullable()->after('class_id')
                  ->constrained('streams')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Na hapa pia tunatumia 'student_attendances'
        Schema::table('student_attendances', function (Blueprint $table) {
            $table->dropForeign(['stream_id']);
            $table->dropColumn('stream_id');
        });
    }
}