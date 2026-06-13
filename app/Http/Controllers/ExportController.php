<?php

namespace App\Http\Controllers;

use App\Exports\ProductsReportExport;
use App\Http\Requests\ExportReportRequest;
use App\Models\ExportHistory;
use App\Services\ExportReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;

class ExportController extends Controller
{
    public function __construct(
        private readonly ExportReportService $exportReportService
    ) {}

    public function index(ExportReportRequest $request): View
    {
        return view('export.index', array_merge(
            $this->exportReportService->indexData($request->filters()),
            [
                'histories' => ExportHistory::with('user:id,name')
                    ->latest()
                    ->paginate(15)
                    ->withQueryString(),
            ]
        ));
    }

    public function reportPdf(ExportReportRequest $request): Response
    {
        $payload = $this->exportReportService->reportPayload($request->filters());

        ExportHistory::create([
            'user_id' => auth()->id(),
            'report_type' => $payload['reportType'],
        ]);

        return $this->buildReportPdf($payload)
            ->download('relatorio-'.$payload['reportType'].'-'.now()->format('Ymd-His').'.pdf');
    }

    public function reportPreviewPdf(ExportReportRequest $request): Response
    {
        $payload = $this->exportReportService->reportPayload($request->filters());

        return $this->buildReportPdf($payload)
            ->stream('preview-relatorio-'.$payload['reportType'].'.pdf');
    }

    public function reportExcel(ExportReportRequest $request): Response
    {
        $payload = $this->exportReportService->reportPayload($request->filters());

        ExportHistory::create([
            'user_id' => auth()->id(),
            'report_type' => $payload['reportType'],
        ]);

        return Excel::download(
            new ProductsReportExport(
                $payload['reportTitle'],
                $payload['rows'],
                $payload['columns'],
                $payload['appliedFilters']
            ),
            'relatorio-'.$payload['reportType'].'-'.now()->format('Ymd-His').'.xlsx'
        );
    }

    private function buildReportPdf(array $payload)
    {
        return Pdf::loadView('export.pdf.products', [
            'reportTitle' => $payload['reportTitle'],
            'rows' => $payload['rows'],
            'columns' => $payload['columns'],
            'generatedAt' => now(),
            'appliedFilters' => $payload['appliedFilters'],
        ])->setPaper('a4', 'portrait');
    }
}
