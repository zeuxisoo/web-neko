<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('memo_attachments', function(Blueprint $table) {
            $table->unsignedSmallInteger('year')->nullable()->index()->after('size');
        });

        Schema::table('memo_attachments', function(Blueprint $table) {
            $table->unsignedTinyInteger('month')->nullable()->index()->after('year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('memo_attachments', function(Blueprint $table) {
            $table->dropIndex('memo_attachments_year_index');
            $table->dropIndex('memo_attachments_month_index');

            $table->dropColumn(['year', 'month']);
        });
    }
};
