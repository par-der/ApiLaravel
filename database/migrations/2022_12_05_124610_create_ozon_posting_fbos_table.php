<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ozon_posting_fbos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('order_number');
            $table->string('posting_number');
            $table->string('status');
            $table->integer('cancel_reason_id');
            //$table->dateTime('posting_created_at', 3)->nullable();
            $table->dateTime('in_process_at', 3);
            $table->json('additional_data');
            $table->unsignedBigInteger('sku');
            $table->string('name');
            $table->integer('quantity');
            $table->string('offer_id');
            $table->float('price');
            $table->json('digital_codes');
            $table->string('region');
            $table->string('city');
            $table->string('delivery_type');
            $table->boolean('is_premium');
            $table->string('payment_type_group_name');
            $table->unsignedBigInteger('warehouse_id');
            $table->string('warehouse_name');
            $table->boolean('is_legal');
            //$table->json('products');
            $table->integer('marketplace_service_item_fulfillment');
            $table->integer('marketplace_service_item_pickup')->nullable();
            $table->integer('marketplace_service_item_dropoff_pvz')->nullable();
            $table->integer('marketplace_service_item_dropoff_sc')->nullable();
            $table->integer('marketplace_service_item_dropoff_ff')->nullable();
            $table->integer('marketplace_service_item_direct_flow_trans')->nullable();
            $table->integer('marketplace_service_item_return_flow_trans')->nullable();
            $table->integer('marketplace_service_item_deliv_to_customer');
            $table->integer('marketplace_service_item_return_not_deliv_to_customer')->nullable();
            $table->integer('marketplace_service_item_return_part_goods_customer')->nullable();
            $table->integer('marketplace_service_item_return_after_deliv_to_customer')->nullable();
            //$table->date('date')->index();

            $table->string('currency_code');
            $table->double('commission_amount');
            $table->integer('commission_percent');
            $table->double('payout');
            $table->unsignedBigInteger('product_id');
            $table->double('old_price');
            $table->double('total_discount_value');
            $table->double('total_discount_percent');
            $table->json('actions');
            $table->json('picking');
            $table->string('client_price');
            $table->string('cluster_from');
            $table->string('cluster_to');

            $table->timestamps();

            $table->unique(['order_id', 'posting_number', 'sku'], 'ozon_posting_fbo_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ozon_posting_fbos');
    }
};
