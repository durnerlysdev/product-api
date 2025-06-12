<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Currency;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Currency::firstOrCreate(['symbol' => 'USD'], [
            'name' => 'US Dollar',
            'exchange_rate' => 1.00,
        ]);

        Currency::firstOrCreate(['symbol' => 'EUR'], [
            'name' => 'Euro',
            'exchange_rate' => 0.92, // Ejemplo de tasa de cambio
        ]);

        Currency::firstOrCreate(['symbol' => 'GBP'], [
            'name' => 'British Pound',
            'exchange_rate' => 0.79, // Ejemplo de tasa de cambio
        ]);
    }
}
