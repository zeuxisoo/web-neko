<?php

namespace App\Api\Version1\Resources\Pulse;

use App\Api\Version1\Bases\ApiResourceCollection;

class TagResourceCollection extends ApiResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = TagResource::class;
}
