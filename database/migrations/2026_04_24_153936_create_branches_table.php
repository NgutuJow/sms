<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
    $table->id();
    $table->foreignId('school_id')->constrained()->onDelete('cascade');

    $table->string('branch_name');
    $table->string('branch_code')->unique();
    $table->string('branch_type')->nullable();
    $table->string('education_level');

    $table->string('email')->nullable();
    $table->string('phone');
    $table->string('alternative_phone')->nullable();

    $table->string('region');
    $table->string('district');
    $table->string('ward');
    $table->string('street')->nullable();
    $table->string('physical_address')->nullable();
    $table->string('postal_address')->nullable();



    $table->boolean('status')->default(1);

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
        Schema::dropIfExists('branches');
    }
}
