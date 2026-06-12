<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_can_be_created(): void
    {
        $response = $this->actingAs(User::factory()->create())
            ->post(route('categories.store'), [
                'name' => 'Defensivos',
                'description' => 'Produtos para protecao de culturas.',
            ]);

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('categories', [
            'name' => 'Defensivos',
            'description' => 'Produtos para protecao de culturas.',
        ]);
    }

    public function test_category_can_be_updated(): void
    {
        $category = Category::factory()->create(['name' => 'Antiga']);

        $response = $this->actingAs(User::factory()->create())
            ->put(route('categories.update', $category), [
                'name' => 'Atualizada',
                'description' => 'Descricao atualizada.',
            ]);

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Atualizada',
        ]);
    }

    public function test_category_can_be_deleted_when_it_has_no_products(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs(User::factory()->create())
            ->delete(route('categories.destroy', $category));

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_category_with_linked_products_cannot_be_deleted(): void
    {
        $category = Category::factory()->create();
        Product::factory()->for($category)->create();

        $response = $this->actingAs(User::factory()->create())
            ->from(route('categories.index'))
            ->delete(route('categories.destroy', $category));

        $response
            ->assertRedirect(route('categories.index'))
            ->assertSessionHasErrors('delete');
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }
}
