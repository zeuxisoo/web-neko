<?php
namespace App\Api\Version1\Controllers;

use Illuminate\Http\Request;
use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\ActivityRequest;
use App\Api\Version1\Repositories\ActivityRepository;
use App\Api\Version1\Transformers\ActivityTransformer;

class ActivityController extends ApiController {

    protected $activityRepository;

    public function __construct(ActivityRepository $activityRepository) {
        $this->activityRepository = $activityRepository;
    }

    public function create(ActivityRequest $request) {
        $input = array_merge(
            $request->only('label_id'),
            $request->only('activity_at'),
            $request->only('remark'),
            [
                'user_id' => $this->auth->user()->id
            ]
        );

        $activity_label = $this->activityRepository->create($input);

        return $this->response->item($activity_label, new ActivityTransformer);
    }

}
