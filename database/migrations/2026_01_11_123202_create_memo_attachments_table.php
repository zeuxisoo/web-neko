<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('memo_attachments', function(Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('memo_id')->nullable()->index();
            $table->string('kind')->default('file')->index();             // e.g. image/video/file
            $table->string('filename');
            $table->string('original_name');
            $table->string('mime_type');        // e.g. 'image/png', 'image/png'
            $table->unsignedBigInteger('size');
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('memo_attachments');
    }
};
