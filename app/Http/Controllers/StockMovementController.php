<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStockMovementRequest;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Services\StockMovementService;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    public function __construct(
        private readonly StockMovementService $stockMovementService
    ) {}

    public function index(Request $request)
    {
        $stockMovements = StockMovement::with(['product', 'productBatch.supplier', 'user'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('product_id')) {
            $stockMovements->where('product_id', $request->product_id);
        }

        if ($request->filled('type')) {
            $stockMovements->where('type', $request->type);
        }

        if ($request->filled('date_from')) {
            $stockMovements->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $stockMovements->whereDate('created_at', '<=', $request->date_to);
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
