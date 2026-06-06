<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductBatch;
use App\Repositories\ExpirationDateRepository;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ExpirationDateService
{
    private const VALID_STATUSES = ['Vencido', 'Vence em breve', 'Seguro'];

    private const VALID_VIEW_MODES = ['product', 'batch'];

    public function __construct(
        private readonly ExpirationDateRepository $expirationDateRepository
    ) {}

    public function indexData(array $query, string $url): array
    {
        $filters = $this->normalizeFilters($query);
        $today = Carbon::now()->startOfDay();

        $items = $filters['view'] === 'batch'
            ? $this->batchItems($filters, $today)
            : $this->productItems($filters, $today);

        return [
            'items' => $this->paginateItems($items, $url, $query),
            'summary' => $this->summary($items),
            'suppliers' => $this->expirationDateRepository->suppliers(),
            'filters' => $filters,
            'viewMode' => $filters['view'],
        ];
    }

    private function productItems(array $filters, Carbon $today): Collection
    {
        return $this->expirationDateRepository
            ->products($filters)
            ->map(function (Product $product) use ($today) {
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

                return array_merge($this->resolveStatus($expirationDate, $today), [
                    'type' => 'product',
                    'product' => $product,
                    'batch' => $nextBatch,
                    'expiration_date' => $expirationDate,
                ]);
            })
            ->filter()
            ->when($filters['status'] !== '', fn ($collection) => $collection->where('status', $filters['status']))
            ->sortBy([
                ['urgency_order', 'asc'],
                ['expiration_date', 'asc'],
                [fn ($item) => $item['product']->name, 'asc'],
            ])
            ->values();
    }

    private function batchItems(array $filters, Carbon $today): Collection
    {
        return $this->expirationDateRepository
            ->batches($filters)
            ->map(function (ProductBatch $batch) use ($today) {
                $expirationDate = Carbon::parse($batch->expiration_date)->startOfDay();

                return array_merge($this->resolveStatus($expirationDate, $today), [
                    'type' => 'batch',
                    'product' => $batch->product,
                    'supplier' => $batch->supplier,
                    'batch' => $batch,
                    'expiration_date' => $expirationDate,
                ]);
            })
            ->when($filters['status'] !== '', fn ($collection) => $collection->where('status', $filters['status']))
            ->sortBy([
                ['urgency_order', 'asc'],
                ['expiration_date', 'asc'],
                [fn ($item) => $item['product']?->name ?? '', 'asc'],
                [fn ($item) => $item['batch']->number ?? '', 'asc'],
            ])
            ->values();
    }

    private function resolveStatus(Carbon $expirationDate, Carbon $today): array
    {
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
    }

    private function paginateItems(Collection $items, string $url, array $query): LengthAwarePaginator
    {
        $perPage = 15;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        return new LengthAwarePaginator(
            $items->forPage($currentPage, $perPage)->values(),
            $items->count(),
            $perPage,
            $currentPage,
            [
                'path' => $url,
                'query' => $query,
            ]
        );
    }

    private function summary(Collection $items): array
    {
        return [
            'expired' => $items->where('status', 'Vencido')->count(),
            'soon' => $items->where('status', 'Vence em breve')->count(),
            'safe' => $items->where('status', 'Seguro')->count(),
        ];
    }

    private function normalizeFilters(array $query): array
    {
        $status = (string) ($query['status'] ?? '');
        if (! in_array($status, self::VALID_STATUSES, true)) {
            $status = '';
        }

        $viewMode = (string) ($query['view'] ?? 'product');
        if (! in_array($viewMode, self::VALID_VIEW_MODES, true)) {
            $viewMode = 'product';
        }

        return [
            'search' => trim((string) ($query['search'] ?? '')),
            'status' => $status,
            'supplier_id' => (string) ($query['supplier_id'] ?? ''),
            'stock_only' => $this->booleanValue($query['stock_only'] ?? false),
            'view' => $viewMode,
        ];
    }

    private function booleanValue(mixed $value): bool
    {
        return in_array($value, [true, 1, '1', 'true', 'on', 'yes'], true);
    }
}
