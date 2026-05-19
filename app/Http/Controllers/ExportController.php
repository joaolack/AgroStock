<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ExportHistory;
use App\Models\Product;
use App\Models\Supplier;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ExportController extends Controller
{
    private const REPORT_TYPES = [
        'general_stock',
        'critical_stock',
        'financial',
        'by_supplier',
        'most_profitable',
    ];

    public function index(Request $request): View
    {
        $filters = $request->validate([
            'report_type' => ['nullable', 'in:general_stock,critical_stock,financial,by_supplier,most_profitable'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'supplier_id' => ['nullable', 'integer', 'exists:suppliers,id'],
            'stock_status' => ['nullable', 'in:all,in_stock,low_stock,out_of_stock'],
            'price_min' => ['nullable', 'numeric', 'min:0'],
            'price_max' => ['nullable', 'numeric', 'min:0'],
        ]);

        if (
            isset($filters['price_min'], $filters['price_max']) &&
            (float) $filters['price_min'] > (float) $filters['price_max']
        ) {
            $filters['price_max'] = $filters['price_min'];
        }

        return view('export.index', [
            'categories' => Category::orderBy('name')->get(['id', 'name']),
            'suppliers' => Supplier::orderBy('name')->get(['id', 'name']),
            'histories' => ExportHistory::with('user:id,name')
                ->latest()
                ->limit(20)
                ->get(),
            'filters' => $filters,
            'insights' => $this->buildExportInsights($filters),
        ]);
    }

    public function reportPdf(Request $request): Response
    {
        [$pdf, $reportType] = $this->buildReportPdf($request);

        ExportHistory::create([
            'user_id' => auth()->id(),
            'report_type' => $reportType,
        ]);

        return $pdf->download('relatorio-' . $reportType . '-' . now()->format('Ymd-His') . '.pdf');
    }

    public function reportPreviewPdf(Request $request): Response
    {
        [$pdf, $reportType] = $this->buildReportPdf($request);
        return $pdf->stream('preview-relatorio-' . $reportType . '.pdf');
    }

    private function buildReportPdf(Request $request): array
    {
        $filters = $request->validate([
            'report_type' => ['required', 'in:general_stock,critical_stock,financial,by_supplier,most_profitable'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'supplier_id' => ['nullable', 'integer', 'exists:suppliers,id'],
            'stock_status' => ['nullable', 'in:all,in_stock,low_stock,out_of_stock'],
            'price_min' => ['nullable', 'numeric', 'min:0'],
            'price_max' => ['nullable', 'numeric', 'min:0'],
        ]);

        if (
            isset($filters['price_min'], $filters['price_max']) &&
            (float) $filters['price_min'] > (float) $filters['price_max']
        ) {
            $filters['price_max'] = $filters['price_min'];
        }

        $query = $this->filteredProductQuery($filters, true)->orderBy('name');

        $products = $query->get();
        $reportType = $filters['report_type'];
        [$reportTitle, $rows, $columns] = $this->buildReportData($reportType, $products);

        $selectedCategory = !empty($filters['category_id'])
            ? Category::find($filters['category_id'])?->name
            : null;
        $selectedSupplier = !empty($filters['supplier_id'])
            ? Supplier::find($filters['supplier_id'])?->name
            : null;

        $stockStatus = $filters['stock_status'] ?? 'all';

        $appliedFilters = [
            'Tipo de relatorio' => $this->reportTypeLabel($reportType),
            'Categoria' => $selectedCategory ?: 'Todas',
            'Fornecedor' => $selectedSupplier ?: 'Todos',
            'Status de estoque' => match ($stockStatus) {
                'in_stock' => 'Estoque normal',
                'low_stock' => 'Estoque baixo',
                'out_of_stock' => 'Sem estoque',
                default => 'Todos',
            },
            'Preco minimo (venda)' => isset($filters['price_min']) ? 'R$ ' . number_format((float) $filters['price_min'], 2, ',', '.') : '-',
            'Preco maximo (venda)' => isset($filters['price_max']) ? 'R$ ' . number_format((float) $filters['price_max'], 2, ',', '.') : '-',
        ];

        $pdf = Pdf::loadView('export.pdf.products', [
            'reportTitle' => $reportTitle,
            'rows' => $rows,
            'columns' => $columns,
            'generatedAt' => now(),
            'appliedFilters' => $appliedFilters,
        ])->setPaper('a4', 'portrait');

        return [$pdf, $reportType];
    }

    private function buildExportInsights(array $filters): array
    {
        $query = $this->filteredProductQuery($filters);

        $totals = (clone $query)
            ->select([
                DB::raw('COALESCE(SUM(selling_price * stock_quantity), 0) as potential_sale_value'),
                DB::raw('COALESCE(SUM((selling_price - cost_price) * stock_quantity), 0) as estimated_profit'),
            ])
            ->first();

        return [
            'potential_sale_value' => (float) ($totals->potential_sale_value ?? 0),
            'estimated_profit' => (float) ($totals->estimated_profit ?? 0),
            'low_stock_items' => (clone $query)
                ->where('stock_quantity', '>', 0)
                ->whereColumn('stock_quantity', '<=', 'minimum_stock')
                ->count(),
        ];
    }

    private function filteredProductQuery(array $filters, bool $withRelations = false)
    {
        $query = $withRelations
            ? Product::with(['category', 'supplier'])
            : Product::query();

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['supplier_id'])) {
            $query->where('supplier_id', $filters['supplier_id']);
        }

        if (!empty($filters['price_min'])) {
            $query->where('selling_price', '>=', $filters['price_min']);
        }

        if (!empty($filters['price_max'])) {
            $query->where('selling_price', '<=', $filters['price_max']);
        }

        $stockStatus = $filters['stock_status'] ?? 'all';
        if ($stockStatus === 'in_stock') {
            $query->whereColumn('stock_quantity', '>', 'minimum_stock');
        } elseif ($stockStatus === 'low_stock') {
            $query->where('stock_quantity', '>', 0)
                ->whereColumn('stock_quantity', '<=', 'minimum_stock');
        } elseif ($stockStatus === 'out_of_stock') {
            $query->where('stock_quantity', '<=', 0);
        }

        return $query;
    }

    private function reportTypeLabel(string $reportType): string
    {
        return match ($reportType) {
            'general_stock' => 'Estoque geral',
            'critical_stock' => 'Estoque critico',
            'financial' => 'Financeiro (custo x venda)',
            'by_supplier' => 'Por fornecedor',
            'most_profitable' => 'Produtos mais lucrativos',
            default => 'Nao definido',
        };
    }

    private function buildReportData(string $reportType, $products): array
    {
        if (!in_array($reportType, self::REPORT_TYPES, true)) {
            $reportType = 'general_stock';
        }

        if ($reportType === 'critical_stock') {
            $critical = $products->filter(function ($product) {
                return (int) $product->stock_quantity <= (int) $product->minimum_stock;
            })->values();

            return [
                'Relatorio de Estoque Critico',
                $critical,
                [
                    ['label' => 'Produto', 'key' => 'name', 'type' => 'text'],
                    ['label' => 'Fornecedor', 'key' => 'supplier.name', 'type' => 'text'],
                    ['label' => 'Estoque atual', 'key' => 'stock_quantity', 'type' => 'int'],
                    ['label' => 'Estoque minimo', 'key' => 'minimum_stock', 'type' => 'int'],
                    ['label' => 'Status', 'key' => 'stock_status', 'type' => 'text'],
                ],
            ];
        }

        if ($reportType === 'financial') {
            return [
                'Relatorio Financeiro (Custo x Venda)',
                $products,
                [
                    ['label' => 'Produto', 'key' => 'name', 'type' => 'text'],
                    ['label' => 'Categoria', 'key' => 'category.name', 'type' => 'text'],
                    ['label' => 'Preco custo', 'key' => 'cost_price', 'type' => 'money'],
                    ['label' => 'Preco venda', 'key' => 'selling_price', 'type' => 'money'],
                ],
            ];
        }

        if ($reportType === 'by_supplier') {
            $grouped = $products->groupBy(function ($product) {
                return $product->supplier?->name ?? 'Sem fornecedor';
            })->map(function ($items, $supplierName) {
                $totalItems = $items->count();
                $totalStock = $items->sum('stock_quantity');
                $stockValue = $items->sum(function ($product) {
                    return (float) $product->cost_price * (float) $product->stock_quantity;
                });

                return (object) [
                    'supplier_name' => $supplierName,
                    'products_count' => $totalItems,
                    'total_stock' => $totalStock,
                    'stock_cost_value' => $stockValue,
                ];
            })->sortBy('supplier_name')->values();

            return [
                'Relatorio por Fornecedor',
                $grouped,
                [
                    ['label' => 'Fornecedor', 'key' => 'supplier_name', 'type' => 'text'],
                    ['label' => 'Qtd. produtos', 'key' => 'products_count', 'type' => 'int'],
                    ['label' => 'Estoque total', 'key' => 'total_stock', 'type' => 'int'],
                    ['label' => 'Valor estoque (custo)', 'key' => 'stock_cost_value', 'type' => 'money'],
                ],
            ];
        }

        if ($reportType === 'most_profitable') {
            $profitable = $products->map(function ($product) {
                $product->unit_margin = (float) $product->selling_price - (float) $product->cost_price;
                return $product;
            })->sortByDesc('unit_margin')->values();

            return [
                'Relatorio de Produtos Mais Lucrativos',
                $profitable,
                [
                    ['label' => 'Produto', 'key' => 'name', 'type' => 'text'],
                    ['label' => 'Categoria', 'key' => 'category.name', 'type' => 'text'],
                    ['label' => 'Preco custo', 'key' => 'cost_price', 'type' => 'money'],
                    ['label' => 'Preco venda', 'key' => 'selling_price', 'type' => 'money'],
                    ['label' => 'Margem unitaria', 'key' => 'unit_margin', 'type' => 'money'],
                ],
            ];
        }

        return [
            'Relatorio de Estoque Geral',
            $products,
            [
                ['label' => 'Produto', 'key' => 'name', 'type' => 'text'],
                ['label' => 'Categoria', 'key' => 'category.name', 'type' => 'text'],
                ['label' => 'Fornecedor', 'key' => 'supplier.name', 'type' => 'text'],
                ['label' => 'Estoque', 'key' => 'stock_quantity', 'type' => 'int'],
                ['label' => 'Preco custo', 'key' => 'cost_price', 'type' => 'money'],
                ['label' => 'Preco venda', 'key' => 'selling_price', 'type' => 'money'],
            ],
        ];
    }
}
