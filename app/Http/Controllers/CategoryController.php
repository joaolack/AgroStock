<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class CategoryController extends Controller
{
    
    public function index(Request $request)
    {
        $query = Category::withCount('products');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $categories = $query
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $totalCategories = Category::count();

        return view('categories.index', compact('categories', 'totalCategories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150', Rule::unique('categories', 'name')],
            'description' => ['nullable', 'string', 'max:1000'],
        ], [
            'name.required' => 'O nome da categoria é obrigatório.',
            'name.unique' => 'Essa categoria já existe.',
            'name.max' => 'O nome da categoria deve ter no máximo 150 caracteres.',
            'description.max' => 'A descrição da categoria não pode exceder 1000 caracteres.',
        ]);

        Category::create($validated);
        return redirect()->route('categories.index')->with('success', 'Categoria criada com sucesso!');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150', Rule::unique('categories', 'name')->ignore($category->id)],
            'description' => ['nullable', 'string', 'max:1000'],
        ], [
            'name.required' => 'O nome da categoria é obrigatório.',
            'name.unique' => 'Essa categoria já existe.',
            'name.max' => 'O nome da categoria deve ter no máximo 150 caracteres.',
            'description.max' => 'A descrição da categoria não pode exceder 1000 caracteres.',
        ]);

        $category->update($validated);
        return redirect()->route('categories.index')->with('success', 'Categoria atualizada com sucesso!');
    }

    public function destroy(Category $category)
    {
        //Verificar produtos vinculados a categoria
        if ($category->products()->count() > 0) {
            return back()->withErrors(['delete' => 'Não é possível excluir esta categoria, pois existem produtos vinculados a ela.']);
        }

        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Categoria excluída com sucesso!');
    }

}
