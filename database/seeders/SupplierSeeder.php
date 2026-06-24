<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'Agro Vale Distribuidora',
                'contact_name' => 'Marina Lopes',
                'phone' => '(11) 4002-1010',
                'email' => 'compras@agrovale.example',
                'address' => 'Rodovia Anhanguera, km 87',
                'city' => 'Campinas',
                'state' => 'SP',
                'zip_code' => '13064-000',
                'notes' => 'Fornecedor principal de fertilizantes e sementes.',
                'active' => true,
            ],
            [
                'name' => 'Vet Campo Ltda',
                'contact_name' => 'Rafael Martins',
                'phone' => '(34) 3355-2200',
                'email' => 'atendimento@vetcampo.example',
                'address' => 'Av. Pecuaria, 450',
                'city' => 'Uberaba',
                'state' => 'MG',
                'zip_code' => '38020-120',
                'notes' => 'Medicamentos refrigerados com entrega semanal.',
                'active' => true,
            ],
            [
                'name' => 'Serrana Insumos Rurais',
                'contact_name' => 'Patricia Gomes',
                'phone' => '(49) 3221-8877',
                'email' => 'pedidos@serranainsumos.example',
                'address' => 'Rua das Cooperativas, 88',
                'city' => 'Lages',
                'state' => 'SC',
                'zip_code' => '88501-320',
                'notes' => 'Defensivos e equipamentos para pequenas propriedades.',
                'active' => true,
            ],
            [
                'name' => 'ProCampo Equipamentos',
                'contact_name' => 'Lucas Andrade',
                'phone' => '(62) 3099-7788',
                'email' => 'vendas@procampo.example',
                'address' => 'Rua dos Implementos, 1020',
                'city' => 'Rio Verde',
                'state' => 'GO',
                'zip_code' => '75901-430',
                'notes' => 'Ferramentas, pulverizadores e itens de reposicao.',
                'active' => true,
            ],
            [
                'name' => 'Fornecedor Inativo Demo',
                'contact_name' => 'Conta Arquivada',
                'phone' => '(00) 0000-0000',
                'email' => 'inativo@demo.example',
                'address' => 'Sem endereco ativo',
                'city' => 'Sao Paulo',
                'state' => 'SP',
                'zip_code' => '01000-000',
                'notes' => 'Registro inativo para demonstrar filtros de fornecedores.',
                'active' => false,
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::updateOrCreate(
                ['email' => $supplier['email']],
                $supplier
            );
        }
    }
}
