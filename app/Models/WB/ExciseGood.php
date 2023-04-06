<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExciseGood extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected
        $fillable = [
        'id',
        'finishedPrice',
        'date',
        'operationTypeId',
        'fiscalDt',
        'docNumber',
        'fnNumber',
        'excise',
    ],
        $casts = [
        'id' => 'integer',
        'finishedPrice' => 'integer',
        'date' => 'date',
        'operationTypeId' => 'integer',
        'fiscalDt' => 'datetime:Y-m-d H:i:s.v',
        'docNumber' => 'integer',
        'fnNumber' => 'string',
        'excise' => 'string',
    ],
        $dates = [
        'date',
        'fiscalDt',
    ];
}
