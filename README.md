<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Description

In its current form, this app:
- Sends a request to the Polygon API to get price data for crypto coins(polygon free tier only allows daily close price).
- Stores the most recent price if it doesn't have it
- Calculates the RSI(Relative Strength Index) if possible. It needs 14 consecutive prices over the same interval to do so(14 consecutive hours, 14 consecutive days, etc). It is currently only configured to track days, this is a limitation of polygon's free tier
- Stores the calculate RSI for the given coin

## Usage

Seeding: Run `php artisan migrate:fresh --seed`. This should create 3 individual coins and two weeks worth of randomized prices

Running the command: After seeding, you can run `php artisan app:get-prices-and-calculate-rsi` to request the previous day's close price of every coin in the coins table and calculate and store rsi for each. These values can be found in the `rsis` table. These values are upserted to the table with each command run.


## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
