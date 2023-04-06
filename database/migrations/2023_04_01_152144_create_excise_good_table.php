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
    public function up(): void
    {
        Schema::create('excise_good', function (Blueprint $table) {
            $table->id();
            $table->integer('finishedPrice')->nullable();
            $table->date('date')->nullable();
            $table->integer('operationTypeId')->nullable();
            $table->dateTime('fiscalDt')->nullable();
            $table->integer('docNumber')->nullable();
            $table->string('fnNumber')->nullable();
            $table->string('excise')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('excise_good');
    }
};
