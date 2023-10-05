<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RSI extends Model
{
    use HasFactory;

    protected $table = 'rsis';

    protected $fillable = ['rsi', 'interval'];

    public function coin(): BelongsTo
    {
        return $this->belongsTo(Coin::class);
    }
}
