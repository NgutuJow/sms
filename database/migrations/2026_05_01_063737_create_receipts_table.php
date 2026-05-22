<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('receipts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('payment_id');
    $table->string('receipt_no')->unique();
    $table->string('pdf_path')->nullable();
    $table->timestamp('issued_at');
});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receipts');
    }
}
