<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsMsdsAndCofa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_order', function (Blueprint $table) {
            $table->boolean('includes_msds')->after('price')->nullable();
            $table->boolean('includes_cofa')->after('includes_msds')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_order', function (Blueprint $table) {
            $table->dropColumn(['includes_msds', 'includes_cofa']);
        });
    }
}
