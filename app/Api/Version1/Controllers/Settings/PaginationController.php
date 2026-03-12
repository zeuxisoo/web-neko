<?php

namespace App\Api\Version1\Controllers\Settings;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\Settings\Pagination\UpdateRequest;
use App\Api\Version1\Resources\Settings\PaginationResource;
use App\Models\Setting;
use App\Services\SettingsService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

class PaginationController extends ApiController
{
    public function __construct(
        private readonly SettingsService $settingsService,
    ) {}

    /**
     * Get pagination settings
     */
    public function index(): JsonResource {
        Gate::authorize('view', Setting::class);

        $settings = [
            'per_page_attachment' => $this->settingsService->get('pagination.per_page_attachment', 2),
            'per_page_bookmark' => $this->settingsService->get('pagination.per_page_bookmark', 8),
            'per_page_comment' => $this->settingsService->get('pagination.per_page_comment', 8),
            'per_page_link' => $this->settingsService->get('pagination.per_page_link', 8),
            'per_page_memo' => $this->settingsService->get('pagination.per_page_memo', 8),
            'per_page_drift' => $this->settingsService->get('pagination.per_page_drift', 8),
        ];

        return new PaginationResource($settings);
    }

    /**
     * Update pagination settings
     */
    public function update(UpdateRequest $request): JsonResource {
        Gate::authorize('update', Setting::class);

        $settings = [
            'pagination.per_page_attachment' => $request->input('per_page_attachment'),
            'pagination.per_page_bookmark' => $request->input('per_page_bookmark'),
            'pagination.per_page_comment' => $request->input('per_page_comment'),
            'pagination.per_page_link' => $request->input('per_page_link'),
            'pagination.per_page_memo' => $request->input('per_page_memo'),
            'pagination.per_page_drift' => $request->input('per_page_drift'),
        ];

        $this->settingsService->setMultiple($settings);

        $cleanedSettings = [];
        foreach ($settings as $key => $value) {
            $newKey = preg_replace('/^pagination\./', '', $key);
            $cleanedSettings[$newKey] = $value;
        }

        return new PaginationResource($cleanedSettings);
    }
}
