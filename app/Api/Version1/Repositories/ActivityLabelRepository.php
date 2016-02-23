<?php
namespace App\Api\Version1\Repositories;

use App\Api\Version1\Bases\ApiRepository;
use App\Models\ActivityLabel;

class ActivityLabelRepository extends ApiRepository {

    public function __construct(ActivityLabel $activity_label) {
        $this->activity_label = $activity_label;
    }

    public function create($input) {
        return (new $this->activity_label)->create($input);
    }

    public function all() {
        return $this->activity_label->orderBy('created_at', 'desc')->paginate();
    }

}
