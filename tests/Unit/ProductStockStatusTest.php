<?php

namespace Tests\Unit;

use App\Models\Product;
use PHPUnit\Framework\TestCase;

class ProductStockStatusTest extends TestCase
{
    public function test_product_with_zero_stock_is_out_of_stock(): void
    {
        $product = new Product([
            'stock_quantity' => 0,
            'minimum_stock' => 5,
        ]);

        $this->assertSame('Em Falta', $product->stock_status);
    }

    public function test_product_with_stock_less_than_or_equal_to_minimum_is_low_stock(): void
    {
        $product = new Product([
            'stock_quantity' => 5,
            'minimum_stock' => 5,
        ]);

        $this->assertSame('Estoque Baixo', $product->stock_status);
    }

    public function test_product_with_stock_greater_than_minimum_is_normal_stock(): void
    {
        $product = new Product([
            'stock_quantity' => 6,
            'minimum_stock' => 5,
        ]);

        $this->assertSame('Estoque Normal', $product->stock_status);
    }
}
