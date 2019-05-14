<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnMsdsDocxToProductsChangeColumnMsdsToMsdsPdfOnProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('msds', 'msds_pdf');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('msds_docx')->after('msds_pdf');
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
            $table->renameColumn('msds_pdf', 'msds');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('msds_docx');
        });
    }
}
