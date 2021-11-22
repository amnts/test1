<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_days', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tariff_id');
            $table->enum('week_day', [1, 2, 3, 4, 5, 6, 7])->index();
            $table->index(['tariff_id', 'week_day']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_days');
    }
}
