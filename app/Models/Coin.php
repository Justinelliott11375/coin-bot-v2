<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CoinPrice;
use App\Models\RSI;
use Carbon\Carbon;

class Coin extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'ticker_name'];

    public function prices()
    {
        return $this->hasMany(CoinPrice::class);
    }

    public function rsis()
    {
        return $this->hasMany(RSI::class);
    }

    /*
    * Format ticker name for polygon API
    */
    public function formattedTickerName(): string
    {
        return 'X:' . $this->attributes['ticker_name'] . 'USD';
    }

    /*
    * Calculate if we have a price saved for every day in the past 2 weeks
    * We need 14 consecutive records to calculate RSI
    */
    public function hasEnoughDataToCalculateRSI(): bool
    {
        $twoWeeksAgo = Carbon::now()->subWeeks(2);

        $count = $this->prices()
            ->where('date', '>=', $twoWeeksAgo)
            ->count();

        return $count >= 14;
    }

    /*
    * Calculate the RSI(Relative Strength Index) for a given coin, over a given time interval(currently only days)
    * RSI is a momentum indicator between 0 and 100 that analyzes pace and variation of movements
    * to indicate when a coin is "overbought"(>70) or "oversold"(<30)
    */
    public function calculateRSI(): int
    {
        // TODO: Maybe make this work with an interval other than weeks(IE last 14 minutes, hours, etc)
        $pricesOverLastTwoWeeks = $this->prices()
            ->orderBy('date', 'desc')
            ->limit(14)
            ->get();

        $priceChanges = [];
        for ($i = 1; $i < count($pricesOverLastTwoWeeks); $i++) {
            $priceChanges[] = $pricesOverLastTwoWeeks[$i - 1]->price - $pricesOverLastTwoWeeks[$i]->price;
        }

        $averageGain = array_sum(array_filter($priceChanges, function ($change) {
            return $change > 0;
        })) / 14;

        $averageLoss = abs(array_sum(array_filter($priceChanges, function ($change) {
            return $change < 0;
        }))) / 14;

        $relativeStrength = $averageGain / $averageLoss;

        return round(100 - (100 / (1 + $relativeStrength)));
    }
}
