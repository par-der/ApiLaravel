<?php

namespace App\Models\Ozon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OzonInfoStock extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected
        $fillable = [
        'product_id',
        'offer_id',
        'stocks',
        'fbo_present',
        'fbo_reserved',
        'fbs_present',
        'fbs_reserved',
        'updated_at',
        'created_at',
        'date',
    ],
        $casts = [
        'product_id' => 'integer',
        'offer_id' => 'string',
        'stocks' => 'json',
        'fbo_present' => 'integer',
        'fbo_reserved' => 'integer',
        'fbs_present' => 'integer',
        'fbs_reserved' => 'integer',
        'updated_at' => 'datetime',
        'created_at' => 'datetime',
        'date' => 'datetime',
    ];

}
