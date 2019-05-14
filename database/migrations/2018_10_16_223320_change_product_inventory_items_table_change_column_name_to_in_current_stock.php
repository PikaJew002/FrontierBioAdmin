<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeProductInventoryItemsTableChangeColumnNameToInCurrentStock extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_inventory_items', function (Blueprint $table) {
            $table->renameColumn('not_in_current_stock', 'in_current_stock');
            $table->boolean('not_in_current_stock')->default($value = true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_inventory_items', function (Blueprint $table) {
            $table->renameColumn('in_current_stock', 'not_in_current_stock');
            $table->boolean('in_current_stock')->default($value = false)->change();
        });
    }
}
