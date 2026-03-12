<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void {
        $defaults = [
            // attachment settings
            ['key' => 'attachment.max_size_kb', 'value' => '8192', 'type' => 'integer'],
            ['key' => 'attachment.allowed_mimes', 'value' => '["jpeg","jpg","png","webp","gif"]', 'type' => 'array'],
            ['key' => 'attachment.max_files', 'value' => '8', 'type' => 'integer'],
            ['key' => 'attachment.max_per_memo', 'value' => '6', 'type' => 'integer'],

            // pagination settings
            ['key' => 'pagination.per_page_attachment', 'value' => '2', 'type' => 'integer'],
            ['key' => 'pagination.per_page_bookmark', 'value' => '8', 'type' => 'integer'],
            ['key' => 'pagination.per_page_comment', 'value' => '8', 'type' => 'integer'],
            ['key' => 'pagination.per_page_link', 'value' => '8', 'type' => 'integer'],
            ['key' => 'pagination.per_page_memo', 'value' => '8', 'type' => 'integer'],
            ['key' => 'pagination.per_page_drift', 'value' => '8', 'type' => 'integer'],
        ];

        foreach ($defaults as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
