<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimetablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timetables', function (Blueprint $table) {
    $table->id();
    $table->foreignId('stream_id')->constrained();
    $table->foreignId('subject_id')->constrained();
    $table->foreignId('teacher_id')->constrained('users'); // Mwalimu anayefundisha
    $table->enum('day_of_week', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']);
    $table->time('start_time');
    $table->time('end_time');
    $table->string('room_no')->nullable(); // Mfano: Lab 1, au Room 5
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('timetables');
    }
}
