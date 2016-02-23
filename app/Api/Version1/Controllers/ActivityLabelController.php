<?php
namespace App\Api\Version1\Controllers;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\ActivityLabelRequest;
use App\Api\Version1\Repositories\ActivityLabelRepository;
use App\Api\Version1\Transformers\ActivityLabelTransformer;

class ActivityLabelController extends ApiController {

    protected $ActivityLabelRepository;

    public function __construct(ActivityLabelRepository $ActivityLabelRepository) {
        $this->ActivityLabelRepository = $ActivityLabelRepository;
    }

    public function create(ActivityLabelRequest $request) {
        $input = array_merge(
            $request->only('name'),
            [
                'user_id' => $this->auth->user()->id
            ]
        );

        $activity_label = $this->ActivityLabelRepository->create($input);

        return $this->response->item($activity_label, new ActivityLabelTransformer);
    }

}
