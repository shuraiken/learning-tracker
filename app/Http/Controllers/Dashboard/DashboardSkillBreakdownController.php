<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardSkillBreakdownController extends Controller
{
    public function __invoke(Request $request, DashboardService $dashboardService)
    {
        $data = $dashboardService->getSkillBreakdown();

        return $this->json($data);
    }
}
