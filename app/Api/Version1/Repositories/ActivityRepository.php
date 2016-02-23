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

}
