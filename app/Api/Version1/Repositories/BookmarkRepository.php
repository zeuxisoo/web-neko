<?php
namespace App\Api\Version1\Repositories;

use App\Api\Version1\Bases\ApiRepository;
use App\Models\Bookmark;

class BookmarkRepository extends ApiRepository {

    public function __construct(Bookmark $bookmark) {
        $this->bookmark = $bookmark;
    }

    public function create($input) {
        return (new $this->bookmark)->create($input);
    }

    public function all() {
        return $this->bookmark->orderBy('create_at', 'asc')->paginate(2);
    }

}
