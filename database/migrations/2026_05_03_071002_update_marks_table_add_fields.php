<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMarksTableAddFields extends Migration
{
    public function up()
    {
        Schema::table('marks', function (Blueprint $table) {
            if (!Schema::hasColumn('marks', 'grade')) {
                $table->string('grade')->nullable()->after('marks');
            }
            if (!Schema::hasColumn('marks', 'marked_by')) {
                $table->foreignId('marked_by')->nullable()->constrained('users')->onDelete('set null')->after('grade');
            }
            if (!Schema::hasColumn('marks', 'marked_date')) {
                $table->timestamp('marked_date')->nullable()->after('marked_by');
            }
            if (!Schema::hasColumn('marks', 'remarks')) {
                $table->text('remarks')->nullable()->after('marked_date');
            }
        });
    }

    public function down()
    {
        Schema::table('marks', function (Blueprint $table) {
            $table->dropColumn(['grade', 'marked_by', 'marked_date', 'remarks']);
        });
    }
}
