<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class ProductSeeder extends Seeder
{
    public function run()
    {
        $usd = Currency::where('symbol', 'USD')->first();
        $eur = Currency::where('symbol', 'EUR')->first();
        $gbp = Currency::where('symbol', 'GBP')->first();

        if ($usd) {
            $product1 = Product::firstOrCreate(
                ['name' => 'Laptop Gamer'],
                [
                    'description' => 'Laptop para gaming.',
                    'price' => 1200.00,
                    'currency_id' => $usd->id,
                    'tax_cost' => 100.00,
                    'manufacturing_cost' => 800.00,
                ]
            );

            // Precios adicionales para Laptop Gamer
            if ($eur) {
                ProductPrice::firstOrCreate(
                    ['product_id' => $product1->id, 'currency_id' => $eur->id],
                    ['price' => 1104.00] // 1200 * 0.92
                );
            }
            if ($gbp) {
                ProductPrice::firstOrCreate(
                    ['product_id' => $product1->id, 'currency_id' => $gbp->id],
                    ['price' => 948.00] // 1200 * 0.79
                );
            }

            $product2 = Product::firstOrCreate(
                ['name' => 'Smartphone Pro'],
                [
                    'description' => 'Smartphone de última generación con cámara avanzada.',
                    'price' => 800.00,
                    'currency_id' => $usd->id,
                    'tax_cost' => 50.00,
                    'manufacturing_cost' => 450.00,
                ]
            );

            // Precios adicionales para Smartphone Pro
            if ($eur) {
                ProductPrice::firstOrCreate(
                    ['product_id' => $product2->id, 'currency_id' => $eur->id],
                    ['price' => 736.00] // 800 * 0.92
                );
            }
        }
    }
}
