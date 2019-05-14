<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnCofa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_inventory_items', function (Blueprint $table) {
            $table->string('cofa')->after('coord3')->nullable();
            $table->boolean('not_in_current_stock')->after('cofa')->default(false);
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
            $table->dropColumn(['cofa', 'not_in_current_stock']);
        });
    }
}
