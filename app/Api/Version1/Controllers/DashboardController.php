<?php
namespace App\Api\Version1\Controllers;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\DashboardRequest;
use App\Api\Version1\Repositories\DashboardRepository;
use App\Api\Version1\Transformers\DashboardTransformer;

class DashboardController extends ApiController {

    protected $dashboardRepository;

    public function __construct(DashboardRepository $dashboardRepository) {
        $this->dashboardRepository = $dashboardRepository;
    }

    public function create(DashboardRequest $request) {
        $input = array_merge(
            $request->only('subject', 'content'),
            [
                'user_id' => $this->auth->user()->id
            ]
        );

        $dashboard = $this->dashboardRepository->create($input);

        return $this->response->item($dashboard, new DashboardTransformer);
    }

}
