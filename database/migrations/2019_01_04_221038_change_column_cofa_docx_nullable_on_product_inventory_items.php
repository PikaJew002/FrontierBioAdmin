<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnCofaDocxNullableOnProductInventoryItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_inventory_items', function (Blueprint $table) {
            $table->string('cofa_docx')->nullable(true)->change();
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
            $table->string('cofa_docx')->nullable(false)->change();
        });
    }
}
