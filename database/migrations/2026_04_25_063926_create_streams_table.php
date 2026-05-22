<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStreamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('streams', function (Blueprint $table) {
    $table->id();
    $table->foreignId('school_class_id')->constrained('school_classes')->onDelete('cascade');
    $table->string('stream_name'); // A, B, Blue, Yellow
    $table->foreignId('teacher_id')->nullable()->constrained('teachers')->onDelete('set null');
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
        Schema::dropIfExists('streams');
    }
}
