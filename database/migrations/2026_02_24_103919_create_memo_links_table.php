<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('memo_links', function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('memo_id')->nullable()->index();
            $table->string('url', 2048);
            $table->string('title', 500)->nullable();
            $table->text('description')->nullable();
            $table->string('image', 2048)->nullable();
            $table->timestamps();

            $table->unique(['memo_id', 'url']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('memo_links', function(Blueprint $table) {
            $table->dropUnique(['memo_id', 'url']);
        });

        Schema::dropIfExists('memo_links');
    }
};
