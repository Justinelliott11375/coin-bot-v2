<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Coin;
use App\Models\CoinPrice;
use Illuminate\Database\Seeder;

class CoinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coinList = [
            ['name' => 'Bitcoin', 'ticker_name' => 'BTC', 'base_price' => 27500],
            ['name' => 'Ethereum', 'ticker_name' => 'ETH', 'base_price' => 1600],
            ['name' => 'Litecoin', 'ticker_name' => 'LTC', 'base_price' => 65],
        ];
        foreach ($coinList as $coinData) {
            $coin = Coin::updateOrCreate(collect($coinData)->except('base_price')->toArray());

            $basePrice = $coinData['base_price'];

            for ($i = 0; $i <= 14; $i++) {
                // Generate a random price within 10 percent of the base_price price
                // $randomPrice = $basePrice + mt_rand(-100, 100) / 1000 * $basePrice;
                $randomPrice = $basePrice + mt_rand(-100, 100) / 1000 * $basePrice;

                CoinPrice::create([
                    'coin_id' => $coin->id,
                    'date' => now()->subDays($i),
                    'price' => $randomPrice,
                ]);
            }
        }
    }
}
