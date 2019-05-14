<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnCofaDocxToProductInventoryItemsChangeColumnCofaToCofaPdfOnProductInventoryItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_inventory_items', function (Blueprint $table) {
            $table->renameColumn('cofa', 'cofa_pdf');
        });

        Schema::table('product_inventory_items', function (Blueprint $table) {
            $table->string('cofa_docx')->after('cofa_pdf');
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
            $table->renameColumn('cofa_pdf', 'cofa');
        });

        Schema::table('product_inventory_items', function (Blueprint $table) {
            $table->dropColumn('cofa_docx');
        });
    }
}
