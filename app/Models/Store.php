<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Store extends Model
{
    /** @use HasFactory<\Database\Factories\StoreFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function products():HasMany{
        return $this->hasMany(Product::class);
    }

    public function orders():HasMany{
        return $this->hasMany(Order::class);
    }
    
    public function reviews():HasMany{
        return $this->hasMany(Review::class);
    }
    
    public function cashflow():HasOne{
        return $this->hasOne(Cashflow::class);
    }
    public function promotions():HasMany{
        return $this->hasMany(Promotion::class);
    }
}
