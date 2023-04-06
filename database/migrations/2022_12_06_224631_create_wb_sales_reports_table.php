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
        Schema::create('report_detail_by_period', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('realizationreport_id');
            $table->date('date_from')->nullable();
            $table->date('date')->nullable();
            $table->date('date_to')->nullable();
            $table->dateTime('create_dt')->nullable();
            $table->string('suppliercontract_code')->nullable();
            $table->unsignedBigInteger('rrd_id')->unique();
            $table->unsignedBigInteger('gi_id');
            $table->string('subject_name')->nullable();
            $table->unsignedBigInteger('nm_id')->nullable();
            $table->string('brand_name')->nullable();
            $table->string('sa_name')->nullable();
            $table->string('ts_name')->nullable();
            $table->string('barcode')->nullable();
            $table->string('doc_type_name');
            $table->integer('quantity');
            $table->float('retail_price');
            $table->float('retail_amount');
            $table->integer('sale_percent');
            $table->float('commission_percent');
            $table->string('office_name')->nullable();
            $table->string('supplier_oper_name');
            $table->date('order_dt');
            $table->date('sale_dt');
            $table->date('rr_dt');
            $table->unsignedBigInteger('shk_id');
            $table->float('retail_price_withdisc_rub');
            $table->integer('delivery_amount');
            $table->integer('return_amount');
            $table->float('delivery_rub');
            $table->string('gi_box_type_name');
            $table->float('product_discount_for_report');
            $table->float('supplier_promo');
            $table->unsignedBigInteger('rid');
            $table->float('ppvz_spp_prc');
            $table->float('ppvz_kvw_prc_base');
            $table->float('ppvz_kvw_prc');
            $table->float('ppvz_sales_commission');
            $table->float('ppvz_for_pay');
            $table->float('ppvz_reward');
            $table->float('acquiring_fee');
            $table->string('acquiring_bank');
            $table->float('ppvz_vw');
            $table->float('ppvz_vw_nds');
            $table->unsignedBigInteger('ppvz_office_id');
            $table->string('ppvz_office_name')->nullable();
            $table->unsignedBigInteger('ppvz_supplier_id');
            $table->string('ppvz_supplier_name')->nullable();
            $table->string('ppvz_inn')->nullable();
            $table->string('declaration_number');
            //$table->string('bonus_type_name')->nullable();
            $table->string('sticker_id')->nullable();
            $table->string('site_country')->nullable();
            $table->float('penalty')->nullable();
            $table->float('additional_payment')->nullable();
            $table->string('srid')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_detail_by_period');
    }
};
