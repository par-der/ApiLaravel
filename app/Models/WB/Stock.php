<?php

namespace App\Models\WB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected
        $fillable = [
        'last_change_date',
        'supplier_article',
        'date',
        'tech_size',
        'barcode',
        'quantity',
        'is_supply',
        'is_realization',
        'quantity_full',
        'warehouse_name',
        'nm_id',
        'subject',
        'category',
        'days_on_site',
        'brand',
        'sccode',
        'price',
        'discount',
    ],
        $casts = [
        'last_change_date' => 'datetime:Y-m-d H:i:s.v',
        'supplier_article' => 'string',
        'date' => 'date',
        'tech_size' => 'string',
        'barcode' => 'string',
        'quantity' => 'integer',
        'is_supply' => 'boolean',
        'is_realization' => 'boolean',
        'quantity_full' => 'integer',
        'warehouse_name' => 'string',
        'nm_id' => 'integer',
        'subject' => 'string',
        'category' => 'string',
        'days_on_site' => 'integer',
        'brand' => 'string',
        'sccode' => 'string',
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
    ],
        $dates = [
        'last_change_date',
    ];

    public function price(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Price::class, 'nm_id', 'nm_id');
    }
}
