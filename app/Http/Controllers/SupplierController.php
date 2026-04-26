<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::withCount('products');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('contact_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $suppliers = $query
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $totalSuppliers = Supplier::count();

        return view('suppliers.index', compact('suppliers', 'totalSuppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|size:2',
            'zip_code' => 'required|string|max:10',
            'notes' => 'nullable|string',
            'active' => 'boolean',
        ]);

        $validated['active'] = $request->boolean('active');

        Supplier::create($validated);

        return redirect()->route('suppliers.index')
            ->with('success', 'Fornecedor cadastrado com sucesso!');
    }

    public function show(Request $request, Supplier $supplier)
    {
        $supplier->load('products');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'supplier' => $supplier,
                'products' => $supplier->products,
            ]);
        }

        return redirect()->route('suppliers.edit', $supplier);
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|size:2',
            'zip_code' => 'required|string|max:10',
            'notes' => 'nullable|string',
            'active' => 'boolean',
        ]);

        $validated['active'] = $request->boolean('active');

        $supplier->update($validated);

        return redirect()->route('suppliers.index')
            ->with('success', 'Fornecedor atualizado com sucesso!');
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->products()->count() > 0) {
            return redirect()->route('suppliers.index')
                ->with('error', 'Não é possivel excluir. Este fornecedor possui produtos vinculados.');
        }

        $supplier->delete();

        return redirect()->route('suppliers.index')
            ->with('success', 'Fornecedor excluido com sucesso!');
    }
}
