<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    
    public function run(): void
    {
        Category::create(['name' => 'Rações e Alimentos', 'description' => 'Produtos para nutrição animal.']);
        Category::create(['name' => 'Medicamentos Veterinários', 'description' => 'Vacinas, vermífugos e tratamentos.']);
        Category::create(['name' => 'Ferramentas e Equipamentos', 'description' => 'Itens para manejo e manutenção.']);
        Category::create(['name' => 'Sementes e Fertilizantes', 'description' => 'Produtos para cultivo e agricultura.']);
    }
}
