<?php

namespace Tests\Feature;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_supplier_can_be_created(): void
    {
        $response = $this->actingAs(User::factory()->create())
            ->post(route('suppliers.store'), $this->validSupplierPayload());

        $response->assertRedirect(route('suppliers.index'));
        $this->assertDatabaseHas('suppliers', [
            'name' => 'Agro Distribuidora',
            'email' => 'contato@agro.test',
            'active' => true,
        ]);
    }

    public function test_supplier_can_be_updated(): void
    {
        $supplier = Supplier::factory()->create(['name' => 'Fornecedor Antigo']);

        $response = $this->actingAs(User::factory()->create())
            ->put(route('suppliers.update', $supplier), $this->validSupplierPayload([
                'name' => 'Fornecedor Atualizado',
                'email' => 'novo@agro.test',
                'active' => '0',
            ]));

        $response->assertRedirect(route('suppliers.index'));
        $this->assertDatabaseHas('suppliers', [
            'id' => $supplier->id,
            'name' => 'Fornecedor Atualizado',
            'email' => 'novo@agro.test',
            'active' => false,
        ]);
    }

    public function test_supplier_can_be_deleted_when_it_has_no_products(): void
    {
        $supplier = Supplier::factory()->create();

        $response = $this->actingAs(User::factory()->create())
            ->delete(route('suppliers.destroy', $supplier));

        $response->assertRedirect(route('suppliers.index'));
        $this->assertDatabaseMissing('suppliers', ['id' => $supplier->id]);
    }

    public function test_supplier_validation_requires_main_fields(): void
    {
        $response = $this->actingAs(User::factory()->create())
            ->from(route('suppliers.create'))
            ->post(route('suppliers.store'), [
                'name' => '',
                'phone' => '',
                'email' => 'invalid-email',
                'address' => '',
                'city' => '',
                'state' => 'SPX',
                'zip_code' => '',
            ]);

        $response
            ->assertRedirect(route('suppliers.create'))
            ->assertSessionHasErrors([
                'name',
                'phone',
                'email',
                'address',
                'city',
                'state',
                'zip_code',
            ]);
        $this->assertDatabaseCount('suppliers', 0);
    }

    private function validSupplierPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Agro Distribuidora',
            'contact_name' => 'Maria Souza',
            'phone' => '(11) 99999-9999',
            'email' => 'contato@agro.test',
            'address' => 'Rua Rural, 100',
            'city' => 'Campinas',
            'state' => 'SP',
            'zip_code' => '13000-000',
            'notes' => 'Entrega semanal.',
            'active' => '1',
        ], $overrides);
    }
}
