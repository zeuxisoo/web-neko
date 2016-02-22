<?php
namespace App\Api\Version1\Repositories;

use App\Api\Version1\Bases\ApiRepository;
use App\Models\Dashboard;

class DashboardRepository extends ApiRepository {

    public function __construct(Dashboard $dashboard) {
        $this->dashboard = $dashboard;
    }

    public function create($input) {
        return (new $this->dashboard)->create($input);
    }

    public function all() {
        return $this->dashboard->orderBy('created_at', 'desc')->paginate(2);
    }

}
