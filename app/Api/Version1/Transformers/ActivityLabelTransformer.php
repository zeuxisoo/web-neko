<?php
namespace App\Api\Version1\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\ActivityLabel;

class ActivityLabelTransformer extends TransformerAbstract {

    public function transform(ActivityLabel $user) {
        return [
            'id' => $user->id,
            'name' => $user->name,
        ];
    }

}
