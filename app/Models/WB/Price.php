<?php

namespace App\Models\WB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{

    use HasFactory;

    protected
        $fillable = [
        'nm_id',
        'price',
        'discount',
        'promoCode',
        'date',
    ],
        $casts = [
        'nm_id' => 'integer',
        'price' => 'decimal:2',
        'discount' => 'integer',
        'promo_code' => 'decimal:2',
        'date' => 'datetime',
    ],
        $dates = [
        'date',
    ];

}
