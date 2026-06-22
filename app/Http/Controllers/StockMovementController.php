<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStockMovementRequest;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Services\StockMovementService;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    public function __construct(
        private readonly StockMovementService $stockMovementService
    ) {}

    public function index(Request $request)
    {
        $timezone = config('app.display_timezone');
        $stockMovements = StockMovement::with(['product', 'productBatch.supplier', 'user'])
            ->orderByDesc('created_at')
            ->orderBy('id');

        if ($request->filled('product_id')) {
            $stockMovements->where('product_id', $request->product_id);
        }

        if ($request->filled('type')) {
            $stockMovements->where('type', $request->type);
        }

        if ($request->filled('date_from')) {
            $stockMovements->where(
                'created_at',
                '>=',
                CarbonImmutable::createFromFormat('Y-m-d', $request->date_from, $timezone)
                    ->startOfDay()
                    ->utc()
            );
        }

        if ($request->filled('date_to')) {
            $stockMovements->where(
                'created_at',
                '<=',
                CarbonImmutable::createFromFormat('Y-m-d', $request->date_to, $timezone)
                    ->endOfDay()
                    ->utc()
            );
        }

        $movements = $stockMovements->paginate(15);
        $products = Product::with('availableBatches')->orderBy('name')->get();
        $suppliers = Supplier::active()->orderBy('name')->get();

        return view('stock-movements.index', compact('movements', 'products', 'suppliers'));
    }

    public function store(StoreStockMovementRequest $request)
    {
        $message = $this->stockMovementService->register($request->validated());

        return redirect()->route('stock-movements.index')
            ->with('success', $message);
    }
}
