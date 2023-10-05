<?php

namespace App\Console\Commands;

use App\Models\Coin;
use App\Models\CoinPrice;
use Carbon\Carbon;
use Illuminate\Console\Command;
use PolygonIO\Rest\Rest;

class GetPricesAndCalculateRSI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-prices-and-calculate-rsi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get price data from polygon API, and calculate RSI if possible';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $today = Carbon::today()->toString();

        $rest = new Rest(env('POLYGON_API_KEY'));

        foreach (Coin::all() as $coin) {

            $response = $rest->crypto->PreviousClose()->get($coin->formattedTickerName());
            // TODO: check response is valid, and if close price we're about to access exists(there is a request limit)

            $price = $response['results'][0]['close'];

            $existingRecord = CoinPrice::where('date', $today)
                ->where('coin_id', $coin->id)
                ->first();

            if (!$existingRecord) {
                $coinPrice = new CoinPrice([
                    'date'  => $today,
                    'price' => $price,
                ]);
                $coin->prices()->save($coinPrice);
            }

            // TODO: Maybe nest this inside of a calculateRsiIfHasEnoughData() or something?
            if ($coin->hasEnoughDataToCalculateRSI()) {
                $rsi = $coin->calculateRSI();
                $coin->rsis()->updateOrCreate([
                    'interval'          => 'day',
                    'coin_id'           => $coin->id,
                ], [
                    'rsi'               => $rsi,
                    'date_calculated'   => $today,
                ]);
            }
        }
    }
}
