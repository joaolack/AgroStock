<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductBatch;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ExpirationDateController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::now()->startOfDay();
        $search = trim((string) $request->query('search', ''));
        $statusFilter = (string) $request->query('status', '');
        $supplierFilter = $request->query('supplier_id');
        $stockOnly = $request->boolean('stock_only');
        $viewMode = (string) $request->query('view', 'product');
        if (! in_array($viewMode, ['product', 'batch'], true)) {
            $viewMode = 'product';
        }

        $validStatuses = ['Vencido', 'Vence em breve', 'Seguro'];
        if (! in_array($statusFilter, $validStatuses, true)) {
            $statusFilter = '';
        }

        $statusResolver = function (Carbon $expirationDate) use ($today): array {
            $daysToExpire = $today->diffInDays($expirationDate, false);

            if ($daysToExpire < 0) {
                return [
                    'days_to_expire' => $daysToExpire,
                    'status' => 'Vencido',
                    'status_class' => 'bg-red-100 text-red-700 border-red-200',
                    'urgency_order' => 0,
                ];
            }

            if ($daysToExpire <= 30) {
                return [
                    'days_to_expire' => $daysToExpire,
                    'status' => 'Vence em breve',
                    'status_class' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                    'urgency_order' => 1,
                ];
            }

            return [
                'days_to_expire' => $daysToExpire,
                'status' => 'Seguro',
                'status_class' => 'bg-green-100 text-green-700 border-green-200',
                'urgency_order' => 2,
            ];
        };

        if ($viewMode === 'batch') {
            $baseQuery = ProductBatch::with(['product.category', 'supplier'])
                ->whereNotNull('expiration_date');

            if ($search !== '') {
                $baseQuery->where(function ($query) use ($search) {
                    $query->where('number', 'like', "%{$search}%")
                        ->orWhereHas('product', fn ($productQuery) => $productQuery->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('supplier', fn ($supplierQuery) => $supplierQuery->where('name', 'like', "%{$search}%"));
                });
            }

            if ($supplierFilter) {
                $baseQuery->where('supplier_id', $supplierFilter);
            }

            if ($stockOnly) {
                $baseQuery->where('quantity', '>', 0);
            }

            $items = $baseQuery
                ->get()
                ->map(function (ProductBatch $batch) use ($statusResolver) {
                    $expirationDate = Carbon::parse($batch->expiration_date)->startOfDay();
                    $statusData = $statusResolver($expirationDate);

                    return array_merge($statusData, [
                        'type' => 'batch',
                        'product' => $batch->product,
                        'supplier' => $batch->supplier,
                        'batch' => $batch,
                        'expiration_date' => $expirationDate,
                    ]);
                })
                ->when($statusFilter !== '', fn ($collection) => $collection->where('status', $statusFilter))
                ->sortBy([
                    ['urgency_order', 'asc'],
                    ['expiration_date', 'asc'],
                    [fn ($item) => $item['product']?->name ?? '', 'asc'],
                    [fn ($item) => $item['batch']->number ?? '', 'asc'],
                ])
                ->values();
        } else {
            $baseQuery = Product::with(['category', 'supplier', 'batches']);

            if ($search !== '') {
                $baseQuery->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhereHas('supplier', fn ($supplierQuery) => $supplierQuery->where('name', 'like', "%{$search}%"));
                });
            }

            if ($supplierFilter) {
                $baseQuery->where('supplier_id', $supplierFilter);
            }

            if ($stockOnly) {
                $baseQuery->where('stock_quantity', '>', 0);
            }

            $items = $baseQuery
                ->get()
                ->map(function (Product $product) use ($statusResolver) {
                    $nextBatch = $product->batches
                        ->where('quantity', '>', 0)
                        ->whereNotNull('expiration_date')
                        ->sortBy(fn ($batch) => $batch->expiration_date?->format('Y-m-d'))
                        ->first();

                    $expirationDate = $nextBatch?->expiration_date ?? $product->expiration_date;
                    if (! $expirationDate) {
                        return null;
                    }

                    $expirationDate = Carbon::parse($expirationDate)->startOfDay();
                    $statusData = $statusResolver($expirationDate);

                    return array_merge($statusData, [
                        'type' => 'product',
                        'product' => $product,
                        'batch' => $nextBatch,
                        'expiration_date' => $expirationDate,
                    ]);
                })
                ->filter()
                ->when($statusFilter !== '', fn ($collection) => $collection->where('status', $statusFilter))
                ->sortBy([
                    ['urgency_order', 'asc'],
                    ['expiration_date', 'asc'],
                    [fn ($item) => $item['product']->name, 'asc'],
                ])
                ->values();
        }

        $perPage = 15;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $paginatedItems = new LengthAwarePaginator(
            $items->forPage($currentPage, $perPage)->values(),
            $items->count(),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        $summary = [
            'expired' => $items->where('status', 'Vencido')->count(),
            'soon' => $items->where('status', 'Vence em breve')->count(),
            'safe' => $items->where('status', 'Seguro')->count(),
        ];

        $suppliers = Supplier::orderBy('name')->get(['id', 'name']);

        $filters = [
            'search' => $search,
            'status' => $statusFilter,
            'supplier_id' => (string) ($supplierFilter ?? ''),
            'stock_only' => $stockOnly,
            'view' => $viewMode,
        ];

        return view('expiration_date.index', [
            'items' => $paginatedItems,
            'summary' => $summary,
            'suppliers' => $suppliers,
            'filters' => $filters,
            'viewMode' => $viewMode,
        ]);
    }
}
