<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeProductFamiliesTableNameColumnToCompoundId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_families', function (Blueprint $table) {
            $table->renameColumn('name', 'compound_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_families', function (Blueprint $table) {
            $table->renameColumn('compound_id', 'name');
        });
    }
}
