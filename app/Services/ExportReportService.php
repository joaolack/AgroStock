<?php

namespace App\Services;

use App\Repositories\ProductReportRepository;
use Illuminate\Support\Collection;

class ExportReportService
{
    private const REPORT_TYPES = [
        'general_stock',
        'critical_stock',
        'financial',
        'by_supplier',
        'most_profitable',
    ];

    public function __construct(
        private readonly ProductReportRepository $productReportRepository
    ) {}

    public function indexData(array $filters): array
    {
        return [
            'categories' => $this->productReportRepository->categories(),
            'suppliers' => $this->productReportRepository->suppliers(),
            'filters' => $filters,
            'insights' => $this->productReportRepository->insights($filters),
        ];
    }

    public function reportPayload(array $filters): array
    {
        $products = $this->productReportRepository->products($filters, true);
        $reportType = $filters['report_type'];
        [$reportTitle, $rows, $columns] = $this->reportData($reportType, $products);

        return [
            'reportType' => $reportType,
            'reportTitle' => $reportTitle,
            'rows' => $rows,
            'columns' => $columns,
            'appliedFilters' => $this->appliedFilters($filters, $reportType),
        ];
    }

    private function appliedFilters(array $filters, string $reportType): array
    {
        $selectedCategory = ! empty($filters['category_id'])
            ? $this->productReportRepository->categoryName($filters['category_id'])
            : null;
        $selectedSupplier = ! empty($filters['supplier_id'])
            ? $this->productReportRepository->supplierName($filters['supplier_id'])
            : null;

        $stockStatus = $filters['stock_status'] ?? 'all';

        return [
            'Tipo de relatório' => $this->reportTypeLabel($reportType),
            'Categoria' => $selectedCategory ?: 'Todas',
            'Fornecedor' => $selectedSupplier ?: 'Todos',
            'Status de estoque' => match ($stockStatus) {
                'in_stock' => 'Estoque normal',
                'low_stock' => 'Estoque baixo',
                'out_of_stock' => 'Sem estoque',
                default => 'Todos',
            },
            'Preco mínimo (venda)' => isset($filters['price_min']) ? 'R$ '.number_format((float) $filters['price_min'], 2, ',', '.') : '-',
            'Preco maximo (venda)' => isset($filters['price_max']) ? 'R$ '.number_format((float) $filters['price_max'], 2, ',', '.') : '-',
        ];
    }

    private function reportTypeLabel(string $reportType): string
    {
        return match ($reportType) {
            'general_stock' => 'Estoque geral',
            'critical_stock' => 'Estoque crítico',
            'financial' => 'Financeiro (custo x venda)',
            'by_supplier' => 'Por fornecedor',
            'most_profitable' => 'Produtos mais lucrativos',
            default => 'Nao definido',
        };
    }

    private function reportData(string $reportType, Collection $products): array
    {
        if (! in_array($reportType, self::REPORT_TYPES, true)) {
            $reportType = 'general_stock';
        }

        if ($reportType === 'critical_stock') {
            $critical = $products->filter(function ($product) {
                return (int) $product->stock_quantity <= (int) $product->minimum_stock;
            })->values();

            return [
                'Relatório de Estoque Crítico',
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
