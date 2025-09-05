<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cashflow extends Model
{
    /** @use HasFactory<\Database\Factories\CashflowFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function store():BelongsTo{
        return $this->belongsTo(Store::class);
    }
}
