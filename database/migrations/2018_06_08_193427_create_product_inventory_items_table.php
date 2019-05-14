<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductInventoryItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_inventory_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->string('lot_number')->unique();
            $table->date('exp_date');
            $table->integer('current_stock');
            $table->string('previous_lot_number');
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
        Schema::dropIfExists('product_inventory_items');
    }
}
