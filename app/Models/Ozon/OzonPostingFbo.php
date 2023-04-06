<?php

namespace App\Models\Ozon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OzonPostingFbo extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected
        $fillable = [
        'order_id',
        'order_number',
        'posting_number',
        'status',
        'cancel_reason_id',
        'posting_created_at',
        'in_process_at',
        'additional_data',
        'sku',
        'name',
        'quantity',
        'offer_id',
        'price',
        'digital_codes',
        'region',
        'city',
        'delivery_type',
        'is_premium',
        'payment_type_group_name',
        'warehouse_id',
        'warehouse_name',
        'is_legal',
        //'products',
        'marketplace_service_item_fulfillment',
        'marketplace_service_item_pickup',
        'marketplace_service_item_dropoff_pvz',
        'marketplace_service_item_dropoff_sc',
        'marketplace_service_item_dropoff_ff',
        'marketplace_service_item_direct_flow_trans',
        'marketplace_service_item_return_flow_trans',
        'marketplace_service_item_deliv_to_customer',
        'marketplace_service_item_return_not_deliv_to_customer',
        'marketplace_service_item_return_part_goods_customer',
        'marketplace_service_item_return_after_deliv_to_customer',
        'updated_at',
        'created_at',
        'product_id',
        'currency_code',
        'actions',
        'picking',
        'commission_amount',
        'commission_percent',
        'payout',
        'product_id',
        'old_price',
        'total_discount_value',
        'total_discount_percent',
        'actions',
        'picking',
        'client_price',
        'cluster_from',
        'cluster_to',
    ],
        $casts = [
        'order_id' => 'integer',
        'order_number' => 'string',
        'posting_number' => 'string',
        'status' => 'string',
        'cancel_reason_id' => 'integer',
        'posting_created_at' => 'datetime',
        'in_process_at' => 'datetime',
        'additional_data' => 'json',
        'sku' => 'integer',
        'name' => 'string',
        'quantity' => 'integer',
        'offer_id' => 'string',
        'price' => 'float',
        'digital_codes' => 'json',
        'region' => 'string',
        'city' => 'string',
        'delivery_type' => 'string',
        'is_premium' => 'boolean',
        'payment_type_group_name' => 'string',
        'warehouse_id' => 'integer',
        'warehouse_name' => 'string',
        'is_legal' => 'boolean',
        //'products' => 'json',
        'marketplace_service_item_fulfillment' => 'integer',
        'marketplace_service_item_pickup' => 'integer',
        'marketplace_service_item_dropoff_pvz' => 'integer',
        'marketplace_service_item_dropoff_sc' => 'integer',
        'marketplace_service_item_dropoff_ff' => 'integer',
        'marketplace_service_item_direct_flow_trans' => 'integer',
        'marketplace_service_item_return_flow_trans' => 'integer',
        'marketplace_service_item_deliv_to_customer' => 'integer',
        'marketplace_service_item_return_not_deliv_to_customer' => 'integer',
        'marketplace_service_item_return_part_goods_customer' => 'integer',
        'marketplace_service_item_return_after_deliv_to_customer' => 'integer',
        'updated_at' => 'datetime',
        'created_at' => 'datetime',
        'product_id' => 'integer',
        'currency_code' => 'string',
        'actions' => 'json',
        'picking' => 'json',
        'commission_amount' => 'double',
        'commission_percent' => 'integer',
        'payout' => 'double',
        'old_price' => 'double',
        'total_discount_value' => 'double',
        'total_discount_percent' => 'double',
        'client_price' => 'string',
        'cluster_from' => 'string',
        'cluster_to' => 'string',
    ];
}