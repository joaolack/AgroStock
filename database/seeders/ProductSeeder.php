<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
   
    public function run(): void
    {
        // Buscar o ID das categorias para facilitar a associação
        $feedId = Category::where('name', 'Rações e Alimentos')->first()->id;
        $medicationId = Category::where('name', 'Medicamentos Veterinários')->first()->id;
        $toolId = Category::where('name', 'Ferramentas e Equipamentos')->first()->id;
        $seedId = Category::where('name', 'Sementes e Fertilizantes')->first()->id;

        Product::create([
            'name' => 'Ração Premium para Cães (15kg)',
            'description' => 'Alimento completo e balanceado para cães adultos.',
            'selling_price' => 150.00,
            'cost_price' => 85.00,
            'category_id' => $feedId,
            'stock_quantity' => 5,        // << ESTOQUE BAIXO (para testar o alerta)
            'minimum_stock' => 10,
            'expiration_date' => now()->addMonths(6),
        ]);

        Product::create([
            'name' => 'Vermífugo de Amplo Espectro',
            'description' => 'Caixa com 4 comprimidos. Uso veterinário.',
            'selling_price' => 45.90,
            'cost_price' => 20.00,
            'category_id' => $medicationId,
            'stock_quantity' => 30,       // << ESTOQUE NORMAL
            'minimum_stock' => 5,
            'expiration_date' => now()->addMonths(2),
        ]);

        Product::create([
            'name' => 'Pá Agrícola Reforçada',
            'description' => 'Cabo de madeira e lâmina em aço carbono.',
            'selling_price' => 89.90,
            'cost_price' => 45.00,
            'category_id' => $toolId,
            'stock_quantity' => 2,        // << ESTOQUE EM FALTA (para testar o alerta)
            'minimum_stock' => 5,
            'expiration_date' => null, // Ferramentas não têm validade
        ]);

        Product::create([
            'name' => 'Fertilizante Orgânico (20kg)',
            'description' => 'Melhora a qualidade do solo e aumenta a produtividade.',
            'selling_price' => 120.00,
            'cost_price' => 70.00,
            'category_id' => $seedId,
            'stock_quantity' => 0,        // << ESTOQUE EM FALTA (para testar o alerta)
            'minimum_stock' => 10,
            'expiration_date' => now()->addMonths(12),
        ]);

        Product::create([
            'name' => 'Semente de Milho Híbrido (10kg)',
            'description' => 'Alto potencial de produtividade. Pacote de 10kg.',
            'selling_price' => 320.00,
            'cost_price' => 250.00,
            'category_id' => $seedId,
            'stock_quantity' => 15,       
            'minimum_stock' => 5,
            'expiration_date' => now()->addDays(20), // Vence em menos de 30 dias (Alerta de validade)
        ]);

        Product::create([
            'name' => 'Vacina Antirrábica 1 dose',
            'description' => 'Vacina obrigatória para cães e gatos. Armazenar refrigerado.',
            'selling_price' => 35.00,
            'cost_price' => 15.00,
            'category_id' => $medicationId,
            'stock_quantity' => 0,        // << ESTOQUE ZERO (Em Falta)
            'minimum_stock' => 20,
            'expiration_date' => now()->addMonths(18),
        ]);

        Product::create([
            'name' => 'Ração para Aves Postura (5kg)',
            'description' => 'Ração completa para galinhas poedeiras.',
            'selling_price' => 25.00,
            'cost_price' => 12.00,
            'category_id' => $feedId,
            'stock_quantity' => 150,      // << Estoque alto
            'minimum_stock' => 30,
            'expiration_date' => now()->addMonths(5),
        ]);

        Product::create([
            'name' => 'Bebedouro Automático 5 Litros',
            'description' => 'Bebedouro por gravidade, ideal para pequenos animais.',
            'selling_price' => 65.00,
            'cost_price' => 35.00,
            'category_id' => $toolId,
            'stock_quantity' => 10,        
            'minimum_stock' => 10,           // << Estoque Mínimo Exato (Atenção)
            'expiration_date' => null,
        ]);

        Product::create([
            'name' => 'Fertilizante NPK 10-10-10 (25kg)',
            'description' => 'Adubo balanceado para uso geral em hortaliças e jardins.',
            'selling_price' => 199.90,
            'cost_price' => 120.00,
            'category_id' => $seedId, 
            'stock_quantity' => 50,
            'minimum_stock' => 15,
            'expiration_date' => now()->addYears(2), // Validade Longa
        ]);

        Product::create([
            'name' => 'Antibiótico Injetável para Bovinos',
            'description' => 'Frasco de 50ml. Uso em casos de infecções sistêmicas.',
            'selling_price' => 180.00,
            'cost_price' => 95.00,
            'category_id' => $medicationId, 
            'stock_quantity' => 3,          // << Estoque Baixo Crítico
            'minimum_stock' => 5,
            'expiration_date' => now()->addDays(5), // << Vencimento Imediato (Máximo Alerta)
        ]);

        Product::create([
            'name' => 'Tesoura de Poda Profissional',
            'description' => 'Lâmina de aço temperado, cabo emborrachado.',
            'selling_price' => 75.00,
            'cost_price' => 38.00,
            'category_id' => $toolId, 
            'stock_quantity' => 10,
            'minimum_stock' => 10,             // << Testa o limite exato
            'expiration_date' => null, 
        ]);

        Product::create([
            'name' => 'Ração para Peixes de Lago',
            'description' => 'Flutuante, pacote de 1kg.',
            'selling_price' => 45.00,
            'cost_price' => 25.00,
            'category_id' => $feedId, 
            'stock_quantity' => 0,          // << ESTOQUE ZERO (Em Falta)
            'minimum_stock' => 20,
            'expiration_date' => now()->addMonths(4), 
        ]);
        Product::where('stock_quantity', '>', 0)->each(function (Product $product) {
            $product->batches()->create([
                'number' => 'INICIAL-' . $product->id,
                'original_quantity' => $product->stock_quantity,
                'quantity' => $product->stock_quantity,
                'expiration_date' => $product->expiration_date,
                'supplier_id' => $product->supplier_id,
            ]);
        });

    }
}
