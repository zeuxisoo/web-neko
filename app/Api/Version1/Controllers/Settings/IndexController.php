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

        return $this->respondJsonData($this->settingsService->all());
    }

    public function clear(Request $request): JsonResponse {
        Gate::authorize('clear', Setting::class);

        $this->settingsService->clearCache();

        return $this->respondJsonMessage('Settings cache cleaned');
    }
}
