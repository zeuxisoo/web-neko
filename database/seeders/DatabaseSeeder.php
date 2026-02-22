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
            ['key' => 'attachment.max_size_kb', 'value' => '8192', 'type' => 'integer'],
            ['key' => 'attachment.allowed_mimes', 'value' => '["jpeg","jpg","png","webp","gif"]', 'type' => 'array'],
            ['key' => 'attachment.max_files', 'value' => '8', 'type' => 'integer'],
            ['key' => 'attachment.max_per_memo', 'value' => '6', 'type' => 'integer'],
        ];

        foreach ($defaults as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
