<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeProductInventoryItemsTableAddColumnsRenameColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_inventory_items', function (Blueprint $table) {
            $table->date('prep_date')->after('lot_number');
            $table->string('coord1')->nullable()->after('current_stock');
            $table->string('coord2')->nullable()->after('coord1');
            $table->string('coord3')->nullable()->after('coord2');
            $table->integer('current_stock')->unsigned()->change();
            $table->string('previous_lot_number')->nullable()->change();
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
            $table->dropColumn('prep_date');
            $table->dropColumn('coord1');
            $table->dropColumn('coord2');
            $table->dropColumn('coord3');
            $table->integer('current_stock')->change();
            $table->string('previous_lot_number')->nullable(false)->change();
        });
    }
}
