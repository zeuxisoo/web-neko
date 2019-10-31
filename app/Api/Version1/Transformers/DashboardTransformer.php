<?php
namespace App\Api\Version1\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Dashboard;

class DashboardTransformer extends TransformerAbstract {

    public function transform(Dashboard $dashboard) {
        return [
            'id'      => $dashboard->id,
            'user'    => $dashboard->user,
            'subject' => base64_encode($dashboard->subject),
            'content' => base64_encode($dashboard->content),
        ];
    }

}
