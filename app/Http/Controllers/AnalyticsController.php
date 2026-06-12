<?php

namespace App\Http\Controllers;

use App\Services\AnalyticsService;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function __construct(
        private readonly AnalyticsService $analyticsService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('analytics.index', $this->analyticsService->dashboardData(
            $request->string('period', '30d')->value(),
            $request->date('start_date'),
            $request->date('end_date'),
            max(1, (int) $request->integer('stale_days', 90))
        ));
    }
}
