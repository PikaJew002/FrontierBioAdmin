<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeProductsTableColumnsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('type', 25)->change();
            $table->decimal('concentration', 4, 2)->nullable()->change();
            $table->string('concentration_units', 10)->nullable()->change();
            $table->string('solvent')->nullable()->change();
            $table->decimal('amount', 7, 2)->change();
            $table->string('amount_units', 10)->nullable()->change();
            $table->decimal('list_price', 7, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('type')->change();
            $table->decimal('concentration', 4, 2)->nullable(false)->change();
            $table->string('concentration_units', 10)->nullable(false)->change();
            $table->string('solvent')->nullable(false)->change();
            $table->string('amount_units')->nullable(false)->change();
            $table->decimal('list_price', 7, 2)->nullable(false)->change();
        });
    }
}
