<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInstallmentsToFeeStructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fee_structures', function (Blueprint $table) {
            $table->boolean('allow_installments')->default(false);
            $table->integer('number_of_installments')->default(1);
            $table->text('installment_dates')->nullable(); // Store due dates for each installment as JSON text
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fee_structures', function (Blueprint $table) {
            $table->dropColumn(['allow_installments', 'number_of_installments', 'installment_dates']);
        });
    }
}