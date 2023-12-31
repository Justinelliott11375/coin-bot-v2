<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoinPrice extends Model
{
    use HasFactory;

    protected $fillable = ['price', 'date'];

    public function coin(): BelongsTo
    {
        return $this->belongsTo(Coin::class);
    }
}
