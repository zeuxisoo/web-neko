<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('memo_bookmarks', function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('memo_id')->index();
            $table->timestamps();

            $table->unique(['user_id', 'memo_id'], 'memo_bookmarks_user_id_memo_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('memo_bookmarks', function(Blueprint $table) {
            $table->dropUnique('memo_bookmarks_user_id_memo_id_unique');
        });

        Schema::dropIfExists('memo_bookmarks');
    }
};
