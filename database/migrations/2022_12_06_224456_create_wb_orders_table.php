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
        Schema::create('wb_orders', function (Blueprint $table) {
            $table->id();
            $table->string('g_number', 50);
            $table->date('date');
            $table->dateTime('last_change_date');
            $table->string('supplier_article', 75);
            $table->string('tech_size', 30);
            $table->string('barcode', 30);
            $table->float('total_price');
            $table->integer('discount_percent');
            $table->string('warehouse_name', 50);
            $table->string('oblast', 200);
            $table->unsignedBigInteger('income_id');
            $table->unsignedBigInteger('odid')->unique();
            $table->unsignedBigInteger('nm_id');
            $table->string('subject', 50);
            $table->string('category', 50);
            $table->string('brand', 50);
            $table->boolean('is_cancel');
            $table->dateTime('cancel_dt');
            $table->string('sticker');
            $table->string('srid');
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
        Schema::dropIfExists('wb_orders');
    }
};
