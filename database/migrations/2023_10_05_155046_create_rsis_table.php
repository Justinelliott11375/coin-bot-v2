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
        Schema::create('rsis', function (Blueprint $table) {
            $table->id();
            $table->float('rsi', 15, 5);
            $table->string('interval', 50)->comment('interval used to calculate RSI(past 14 days, weeks, etc)');
            $table->foreignIdFor(Coin::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rsis');
    }
};
