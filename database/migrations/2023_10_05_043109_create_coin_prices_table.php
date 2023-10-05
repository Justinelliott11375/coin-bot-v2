<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Coin;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coin_prices', function (Blueprint $table) {
            $table->id();
            $table->float('price', 15, 5);
            $table->date('date')->comment('Date that this close price is for');
            $table->foreignIdFor(Coin::class);
            $table->timestamps();

            $table->unique(['coin_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coin_prices');
    }
};
