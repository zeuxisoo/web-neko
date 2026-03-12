<?php

namespace App\Api\Version1\Controllers\Settings;

use App\Api\Version1\Bases\ApiController;
use App\Models\Setting;
use App\Services\SettingsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class IndexController extends ApiController
{
    public function __construct(
        private readonly SettingsService $settingsService,
    ) {}

    public function index(Request $request): JsonResponse {
        Gate::authorize('viewAny', Setting::class);

        $allSettings = $this->settingsService->all();

        $settingsConfig = [
            'pagination' => [
                'per_page_attachment' => ['key' => 'pagination.per_page_attachment', 'default' => 2],
                'per_page_bookmark' => ['key' => 'pagination.per_page_bookmark', 'default' => 8],
                'per_page_comment' => ['key' => 'pagination.per_page_comment', 'default' => 8],
                'per_page_link' => ['key' => 'pagination.per_page_link', 'default' => 8],
                'per_page_memo' => ['key' => 'pagination.per_page_memo', 'default' => 8],
                'per_page_drift' => ['key' => 'pagination.per_page_drift', 'default' => 8],
            ],
            'attachment' => [
                'max_size_kb' => ['key' => 'attachment.max_size_kb', 'default' => 8192],
                'allowed_mimes' => ['key' => 'attachment.allowed_mimes', 'default' => ['jpeg', 'jpg', 'png', 'webp', 'gif']],
                'max_files' => ['key' => 'attachment.max_files', 'default' => 8],
                'max_per_memo' => ['key' => 'attachment.max_per_memo', 'default' => 6],
            ],
        ];

        $processedSettings = [];
        foreach ($settingsConfig as $category => $settings) {
            $processedSettings[$category] = [];
            foreach ($settings as $shortKey => $config) {
                $processedSettings[$category][$shortKey] = $allSettings[$config['key']] ?? $config['default'];
            }
        }

        return $this->respondJsonData($processedSettings);
    }

    public function clear(Request $request): JsonResponse {
        Gate::authorize('clear', Setting::class);

        $this->settingsService->clearCache();

        return $this->respondJsonMessage('Settings cache cleaned');
    }
}
