<?php

namespace App\Api\Version1\Controllers\Settings;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\Settings\Attachment\UpdateRequest;
use App\Api\Version1\Resources\Settings\AttachmentResource;
use App\Models\Setting;
use App\Services\SettingsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

class AttachmentController extends ApiController
{
    public function __construct(
        private readonly SettingsService $settingsService,
    ) {}

    /**
     * Get attachment settings
     */
    public function index(): JsonResource {
        Gate::authorize('view', Setting::class);

        $settings = [
            'max_size_kb' => $this->settingsService->get('attachment.max_size_kb', 8192),
            'allowed_mimes' => $this->settingsService->get('attachment.allowed_mimes', ['jpeg', 'jpg', 'png', 'webp', 'gif']),
            'max_files' => $this->settingsService->get('attachment.max_files', 8),
            'max_per_memo' => $this->settingsService->get('attachment.max_per_memo', 6),
        ];

        return new AttachmentResource($settings);
    }

    /**
     * Update attachment settings
     */
    public function update(UpdateRequest $request): JsonResponse {
        Gate::authorize('update', Setting::class);

        $this->settingsService->setMultiple([
            'attachment.max_size_kb' => $request->input('max_size_kb'),
            'attachment.allowed_mimes' => $request->input('allowed_mimes'),
            'attachment.max_files' => $request->input('max_files'),
            'attachment.max_per_memo' => $request->input('max_per_memo'),
        ]);

        return $this->respondJsonMessage('Settings updated successfully');
    }
}
