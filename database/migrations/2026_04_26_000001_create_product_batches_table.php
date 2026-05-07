<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->string('number');
            $table->integer('original_quantity')->default(0);
            $table->integer('quantity')->default(0);
            $table->date('expiration_date')->nullable();
            $table->timestamps();

            $table->unique(['product_id', 'number']);
            $table->index(['product_id', 'expiration_date', 'created_at']);
        });

        DB::table('products')
            ->where('stock_quantity', '>', 0)
            ->orderBy('id')
            ->get()
            ->each(function ($product) {
                DB::table('product_batches')->insert([
                    'product_id' => $product->id,
                    'supplier_id' => $product->supplier_id ?? null,
                    'number' => 'INICIAL-' . $product->id,
                    'original_quantity' => $product->stock_quantity,
                    'quantity' => $product->stock_quantity,
                    'expiration_date' => $product->expiration_date ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });

        Schema::table('stock_movements', function (Blueprint $table) {
            $table->foreignId('product_batch_id')
                ->nullable()
                ->after('product_id')
                ->constrained('product_batches')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropForeign(['product_batch_id']);
            $table->dropColumn('product_batch_id');
        });

        Schema::dropIfExists('product_batches');
    }
};
