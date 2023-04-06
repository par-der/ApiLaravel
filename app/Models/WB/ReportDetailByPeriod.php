<?php

namespace App\Models\WB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportDetailByPeriod extends Model
{
    use HasFactory;

    protected
        $table = 'report_detail_by_period',
        $fillable = [
        'realizationreport_id',
        'date_from',
        'date_to',
        'date',
        'create_dt',
        'suppliercontract_code',
        'rrd_id',
        'gi_id',
        'subject_name',
        'nm_id',
        'brand_name',
        'sa_name',
        'ts_name',
        'barcode',
        'doc_type_name',
        'quantity',
        'retail_price',
        'retail_amount',
        'sale_percent',
        'commission_percent',
        'office_name',
        'supplier_oper_name',
        'order_dt',
        'sale_dt',
        'rr_dt',
        'shk_id',
        'retail_price_withdisc_rub',
        'delivery_amount',
        'return_amount',
        'delivery_rub',
        'gi_box_type_name',
        'product_discount_for_report',
        'supplier_promo',
        'rid',
        'ppvz_spp_prc',
        'ppvz_kvw_prc_base',
        'ppvz_kvw_prc',
        'ppvz_sales_commission',
        'ppvz_for_pay',
        'ppvz_reward',
        'acquiring_fee',
        'acquiring_bank',
        'ppvz_vw',
        'ppvz_vw_nds',
        'ppvz_office_id',
        'ppvz_office_name',
        'ppvz_supplier_id',
        'ppvz_supplier_name',
        'ppvz_inn',
        'declaration_number',
        'bonus_type_name',
        'sticker_id',
        'site_country',
        'penalty',
        'additional_payment',
        'kiz',
        'srid',
    ],
        $casts = [
        'realizationreport_id' => 'integer',
        'date_from' => 'datetime',
        'date_to' => 'datetime',
        'date' => 'date',
        'create_dt' => 'datetime',
        'suppliercontract_code' => 'string',
        'rrd_id' => 'integer',
        'gi_id' => 'integer',
        'subject_name' => 'string',
        'nm_id' => 'integer',
        'brand_name' => 'string',
        'sa_name' => 'string',
        'ts_name' => 'string',
        'barcode' => 'string',
        'doc_type_name' => 'string',
        'retail_price' => 'decimal:2',
        'retail_amount' => 'decimal:2',
        'sale_percent' => 'integer',
        'commission_percent' => 'decimal:2',
        'office_name' => 'string',
        'order_dt' => 'datetime',
        'sale_dt' => 'datetime',
        'rr_dt' => 'datetime',
        'shk_id' => 'integer',
        'retail_price_withdisc_rub' => 'decimal:2',
        'delivery_amount' => 'integer',
        'return_amount' => 'integer',
        'delivery_rub' => 'decimal:2',
        'gi_box_type_name' => 'string',
        'product_discount_for_report' => 'decimal:2',
        'supplier_promo' => 'decimal:2',
        'rid' => 'string',
        'ppvz_spp_prc' => 'decimal:2',
        'ppvz_kvw_prc_base' => 'decimal:2',
        'ppvz_kvw_prc' => 'decimal:2',
        'ppvz_sales_commission' => 'decimal:2',
        'ppvz_for_pay' => 'decimal:2',
        'ppvz_reward' => 'decimal:2',
        'acquiring_fee' => 'decimal:2',
        'acquiring_bank' => 'string',
        'ppvz_vw' => 'decimal:2',
        'ppvz_vw_nds' => 'decimal:2',
        'ppvz_office_id' => 'integer',
        'ppvz_office_name' => 'string',
        'ppvz_supplier_id' => 'integer',
        'ppvz_supplier_name' => 'string',
        'ppvz_inn' => 'string',
        'declaration_number' => 'string',
        'bonus_type_name' => 'string',
        'sticker_id' => 'string',
        'site_country' => 'string',
        'penalty' => 'decimal:2',
        'additional_payment' => 'decimal:2',
        'kiz' => 'string',
        'srid' => 'string',
    ];

    public function price(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Price::class, 'nm_id', 'nm_id');
    }
}
