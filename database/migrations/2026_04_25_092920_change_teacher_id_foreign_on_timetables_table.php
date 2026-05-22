<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTeacherIdForeignOnTimetablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
{
    Schema::table('timetables', function (Blueprint $table) {
        // 1. Futa constraint ya zamani inayoelekea 'users'
        $table->dropForeign(['teacher_id']);
        
        // 2. Tengeneza constraint mpya inayoelekea 'teachers'
        $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
