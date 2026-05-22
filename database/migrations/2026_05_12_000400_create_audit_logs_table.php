<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('action'); // create, update, delete, approve, reject
            $table->string('model'); // Expense, Fine, PayrollRecord, etc.
            $table->unsignedBigInteger('model_id');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->text('changes')->nullable(); // JSON of what changed
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('audit_logs');
    }
};
