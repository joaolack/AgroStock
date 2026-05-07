<?php

namespace App\Http\Controllers;

use App\Models\Product; 
use App\Models\ProductBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $criticalStock = Product::whereColumn('stock_quantity', '<=', 'minimum_stock')
                                   ->with('category')
                                   ->orderBy('stock_quantity', 'asc')
                                   ->get();
                                   
        $closeToExpiry = ProductBatch::with('product')
            ->where('quantity', '>', 0)
            ->whereNotNull('expiration_date')
            ->whereDate('expiration_date', '>=', Carbon::now()->startOfDay())
            ->whereDate('expiration_date', '<=', Carbon::now()->addDays(60)->endOfDay())   
            ->orderBy('expiration_date', 'asc')
            ->get();
        
        $totalProducts = Product::count();
        $outOfStockProducts = Product::where('stock_quantity', 0)->count();

        $totalStockValue = Product::select(DB::raw('SUM(cost_price * stock_quantity) as total_cost'))
                                      ->value('total_cost') ?? 0;

        return view('dashboard', compact(
            'criticalStock', 
            'closeToExpiry', 
            'totalProducts', 
            'outOfStockProducts', 
            'totalStockValue'
        ));                              
    }
}
