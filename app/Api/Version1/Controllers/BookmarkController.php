<?php
namespace App\Api\Version1\Controllers;

use App\Api\Version1\Bases\ApiController;
use App\Api\Version1\Requests\BookmarkRequest;
use App\Api\Version1\Repositories\BookmarkRepository;
use App\Api\Version1\Transformers\BookmarkTransformer;

class BookmarkController extends ApiController {

    protected $bookmarkRepository;

    public function __construct(BookmarkRepository $bookmarkRepository) {
        $this->bookmarkRepository = $bookmarkRepository;
    }

    public function create(BookmarkRequest $request) {
        $input = array_merge(
            $request->only('content'),
            [
                'user_id' => $this->auth->user()->id
            ]
        );

        $dashboard = $this->bookmarkRepository->create($input);

        return $this->response->item($dashboard, new BookmarkTransformer);
    }

}
