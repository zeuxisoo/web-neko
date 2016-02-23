<?php
namespace App\Api\Version1\Controllers;

use Illuminate\Http\Request;
use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\ActivityLabelRequest;
use App\Api\Version1\Repositories\ActivityLabelRepository;
use App\Api\Version1\Transformers\ActivityLabelTransformer;

class ActivityLabelController extends ApiController {

    protected $activityLabelRepository;

    public function __construct(ActivityLabelRepository $ActivityLabelRepository) {
        $this->activityLabelRepository = $ActivityLabelRepository;
    }

    public function create(ActivityLabelRequest $request) {
        $input = array_merge(
            $request->only('name'),
            [
                'user_id' => $this->auth->user()->id
            ]
        );

        $activity_label = $this->activityLabelRepository->create($input);

        return $this->response->item($activity_label, new ActivityLabelTransformer);
    }

    public function all() {
        $activity_labels = $this->activityLabelRepository->all();

        return $this->response->paginator($activity_labels, new ActivityLabelTransformer);
    }

    public function destory(Request $request) {
        $input = $request->only('id');

        $activity_labels = $this->activityLabelRepository->destory($input['id']);

        return $this->response->item($activity_labels, new ActivityLabelTransformer);
    }

}
