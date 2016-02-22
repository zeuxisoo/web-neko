<?php
namespace App\Api\Version1\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Bookmark;

class BookmarkTransformer extends TransformerAbstract {

    public function transform(Bookmark $bookmark) {
        return [
            'id' => $bookmark->id,
            'user' => $bookmark->user,
            'content' => $bookmark->content,
            'created_at' => $bookmark->created_at->toDateTimeString()
        ];
    }

}
