<?php
namespace App\Api\Version1\Repositories;

use App\Api\Version1\Bases\ApiRepository;
use App\Models\Activity;

class ActivityRepository extends ApiRepository {

    public function __construct(Activity $activity) {
        $this->activity = $activity;
    }

    public function create($input) {
        return (new $this->activity)->create($input);
    }

    public function all($input) {
        $per_page = 10;

        if (empty($input['label']) === true) {
            return $this->activity->orderBy('created_at', 'desc')->paginate($per_page);
        }else{
            return $this->activity
                        ->whereLabelId($input['label'])
                        ->orderBy('created_at', 'desc')
                        ->paginate($per_page);
        }
    }

}
