<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAllForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Change id columns to unsigned integers
        Schema::table('contacts', function (Blueprint $table) {
            $table->integer('customer_id')->unsigned()->nullable()->change();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->integer('contact_id')->unsigned()->nullable()->change();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->integer('family_id')->unsigned()->nullable()->change();
        });

        Schema::table('product_inventory_items', function (Blueprint $table) {
            $table->integer('product_id')->unsigned()->nullable()->change();
        });

        Schema::table('item_order', function (Blueprint $table) {
            $table->integer('item_id')->unsigned()->change();
            $table->integer('order_id')->unsigned()->change();
        });


        // Add foreign keys
        Schema::table('contacts', function (Blueprint $table) {
            $table->foreign('customer_id')->references('id')->on('customers');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('contact_id')->references('id')->on('contacts');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreign('family_id')->references('id')->on('product_families');
        });

        Schema::table('product_inventory_items', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products');
        });

        Schema::table('item_order', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('product_inventory_items');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['contact_id']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['family_id']);
        });

        Schema::table('product_inventory_items', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        Schema::table('item_order', function (Blueprint $table) {
            $table->dropForeign(['item_id', 'order_id']);
        });
    }
}
