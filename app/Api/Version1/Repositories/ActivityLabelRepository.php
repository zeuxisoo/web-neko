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

    public function destory($id) {
        $activity_label = $this->activity_label->find($id);

        if (empty($activity_label->id) === false) {
            $activity_label->delete();
        }

        return $activity_label;
    }

}
