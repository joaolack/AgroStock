<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Nutricao Animal',
                'description' => 'Racoes, suplementos e concentrados para manejo nutricional.',
            ],
            [
                'name' => 'Medicamentos Veterinarios',
                'description' => 'Vacinas, vermifugos, antibioticos e tratamentos de uso veterinario.',
            ],
            [
                'name' => 'Sementes e Fertilizantes',
                'description' => 'Insumos para plantio, correcao de solo e adubacao.',
            ],
            [
                'name' => 'Defensivos Agricolas',
                'description' => 'Produtos para controle de plantas daninhas, pragas e doencas.',
            ],
            [
                'name' => 'Ferramentas e Equipamentos',
                'description' => 'Itens para manejo, manutencao e operacao no campo.',
            ],
            [
                'name' => 'EPI e Higiene',
                'description' => 'Equipamentos de protecao individual e itens de limpeza operacional.',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description']]
            );
        }
    }
}
