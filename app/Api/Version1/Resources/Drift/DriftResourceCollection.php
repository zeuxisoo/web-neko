<?php

namespace App\Api\Version1\Resources\Drift;

use App\Api\Version1\Bases\ApiResourceCollection;

class DriftResourceCollection extends ApiResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = DriftResource::class;
}
