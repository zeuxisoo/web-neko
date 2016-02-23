<?php
namespace App\Api\Version1\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Activity;

class ActivityTransformer extends TransformerAbstract {

    public function transform(Activity $activity) {
        return [
            'id'          => $activity->id,
            'label'       => $activity->label,
            'activity_at' => $activity->name,
            'remark'      => $activity->remark
        ];
    }

}
