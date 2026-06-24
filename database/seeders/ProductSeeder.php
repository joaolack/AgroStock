<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::pluck('id', 'name');
        $suppliers = Supplier::pluck('id', 'name');

        $products = [
            [
                'name' => 'Racao Aves Postura 40kg',
                'description' => 'Racao completa para galinhas poedeiras em fase produtiva.',
                'selling_price' => 138.90,
                'cost_price' => 92.50,
                'minimum_stock' => 35,
                'category' => 'Nutricao Animal',
                'supplier' => 'Agro Vale Distribuidora',
                'expiration_date' => now()->addMonths(5)->toDateString(),
                'batches' => [
                    ['number' => 'DEMO-RAC-2606-A', 'original_quantity' => 180, 'quantity' => 140, 'expiration_date' => now()->addMonths(5)->toDateString()],
                ],
            ],
            [
                'name' => 'Suplemento Mineral Bovino 30kg',
                'description' => 'Mistura mineral pronta para bovinos de corte em pastejo.',
                'selling_price' => 119.90,
                'cost_price' => 78.00,
                'minimum_stock' => 25,
                'category' => 'Nutricao Animal',
                'supplier' => 'Agro Vale Distribuidora',
                'expiration_date' => now()->addDays(45)->toDateString(),
                'batches' => [
                    ['number' => 'DEMO-SUP-2604-A', 'original_quantity' => 18, 'quantity' => 14, 'expiration_date' => now()->addDays(45)->toDateString()],
                    ['number' => 'DEMO-SUP-2603-V', 'original_quantity' => 4, 'quantity' => 4, 'expiration_date' => now()->subDays(8)->toDateString()],
                ],
            ],
            [
                'name' => 'Semente de Milho Hibrido 20kg',
                'description' => 'Semente tratada para plantio de alto potencial produtivo.',
                'selling_price' => 449.00,
                'cost_price' => 318.00,
                'minimum_stock' => 15,
                'category' => 'Sementes e Fertilizantes',
                'supplier' => 'Agro Vale Distribuidora',
                'expiration_date' => now()->addDays(24)->toDateString(),
                'batches' => [
                    ['number' => 'DEMO-MIL-2606-A', 'original_quantity' => 25, 'quantity' => 8, 'expiration_date' => now()->addDays(24)->toDateString()],
                ],
            ],
            [
                'name' => 'Fertilizante NPK 10-10-10 25kg',
                'description' => 'Fertilizante granulado balanceado para culturas anuais.',
                'selling_price' => 214.90,
                'cost_price' => 142.00,
                'minimum_stock' => 20,
                'category' => 'Sementes e Fertilizantes',
                'supplier' => 'Serrana Insumos Rurais',
                'expiration_date' => now()->addMonths(18)->toDateString(),
                'batches' => [
                    ['number' => 'DEMO-NPK-2606-A', 'original_quantity' => 60, 'quantity' => 50, 'expiration_date' => now()->addMonths(18)->toDateString()],
                    ['number' => 'DEMO-NPK-2605-B', 'original_quantity' => 30, 'quantity' => 22, 'expiration_date' => now()->addDays(58)->toDateString()],
                ],
            ],
            [
                'name' => 'Vacina Clostridioses 50 doses',
                'description' => 'Vacina refrigerada para manejo sanitario de bovinos.',
                'selling_price' => 189.00,
                'cost_price' => 111.00,
                'minimum_stock' => 20,
                'category' => 'Medicamentos Veterinarios',
                'supplier' => 'Vet Campo Ltda',
                'expiration_date' => now()->addDays(12)->toDateString(),
                'batches' => [
                    ['number' => 'DEMO-VAC-2606-A', 'original_quantity' => 30, 'quantity' => 12, 'expiration_date' => now()->addDays(12)->toDateString()],
                ],
            ],
            [
                'name' => 'Antiparasitario Bovino 500ml',
                'description' => 'Produto veterinario para controle de parasitas internos e externos.',
                'selling_price' => 96.50,
                'cost_price' => 54.20,
                'minimum_stock' => 10,
                'category' => 'Medicamentos Veterinarios',
                'supplier' => 'Vet Campo Ltda',
                'expiration_date' => now()->addMonths(9)->toDateString(),
                'batches' => [],
            ],
            [
                'name' => 'Herbicida Pos-emergente 1L',
                'description' => 'Defensivo para controle pos-emergente de plantas invasoras.',
                'selling_price' => 87.90,
                'cost_price' => 49.90,
                'minimum_stock' => 8,
                'category' => 'Defensivos Agricolas',
                'supplier' => 'Serrana Insumos Rurais',
                'expiration_date' => now()->subDays(7)->toDateString(),
                'batches' => [
                    ['number' => 'DEMO-HER-2602-V', 'original_quantity' => 10, 'quantity' => 4, 'expiration_date' => now()->subDays(7)->toDateString()],
                ],
            ],
            [
                'name' => 'Pulverizador Costal 20L',
                'description' => 'Pulverizador manual com reservatorio de 20 litros.',
                'selling_price' => 269.90,
                'cost_price' => 172.00,
                'minimum_stock' => 5,
                'category' => 'Ferramentas e Equipamentos',
                'supplier' => 'ProCampo Equipamentos',
                'expiration_date' => null,
                'batches' => [
                    ['number' => 'DEMO-PUL-2606-A', 'original_quantity' => 10, 'quantity' => 6, 'expiration_date' => null],
                ],
            ],
            [
                'name' => 'Pa Agricola Reforcada',
                'description' => 'Pa com cabo de madeira e lamina em aco carbono.',
                'selling_price' => 74.90,
                'cost_price' => 38.00,
                'minimum_stock' => 6,
                'category' => 'Ferramentas e Equipamentos',
                'supplier' => 'ProCampo Equipamentos',
                'expiration_date' => null,
                'batches' => [
                    ['number' => 'DEMO-PA-2605-A', 'original_quantity' => 14, 'quantity' => 14, 'expiration_date' => null],
                ],
            ],
            [
                'name' => 'Luva Nitrilica Rural caixa 100 un',
                'description' => 'Luvas nitrilicas para manuseio de insumos e produtos veterinarios.',
                'selling_price' => 59.90,
                'cost_price' => 31.50,
                'minimum_stock' => 12,
                'category' => 'EPI e Higiene',
                'supplier' => 'ProCampo Equipamentos',
                'expiration_date' => null,
                'batches' => [
                    ['number' => 'DEMO-LUV-2604-A', 'original_quantity' => 30, 'quantity' => 30, 'expiration_date' => null],
                ],
            ],
        ];

        foreach ($products as $productData) {
            $batches = $productData['batches'];
            $stockQuantity = collect($batches)->sum('quantity');
            $supplierId = $suppliers[$productData['supplier']] ?? null;

            $product = Product::updateOrCreate(
                ['name' => $productData['name']],
                [
                    'description' => $productData['description'],
                    'selling_price' => $productData['selling_price'],
                    'cost_price' => $productData['cost_price'],
                    'stock_quantity' => $stockQuantity,
                    'minimum_stock' => $productData['minimum_stock'],
                    'expiration_date' => $productData['expiration_date'],
                    'category_id' => $categories[$productData['category']],
                    'supplier_id' => $supplierId,
                ]
            );

            $this->syncBatches($product, $supplierId, $batches);
        }
    }

    private function syncBatches(Product $product, ?int $supplierId, array $batches): void
    {
        $batchNumbers = collect($batches)->pluck('number')->all();
        $demoBatches = $product->batches()->where('number', 'like', 'DEMO-%');

        $batchNumbers === []
            ? $demoBatches->delete()
            : $demoBatches->whereNotIn('number', $batchNumbers)->delete();

        foreach ($batches as $batch) {
            $product->batches()->updateOrCreate(
                ['number' => $batch['number']],
                [
                    'supplier_id' => $supplierId,
                    'original_quantity' => $batch['original_quantity'],
                    'quantity' => $batch['quantity'],
                    'expiration_date' => $batch['expiration_date'],
                ]
            );
        }
    }
}
