<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSuppliersProductsPivotsMeasureUnitIdColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suppliers_products_pivots', function (Blueprint $table) {
            //
            $table->bigInteger('measure_unit_id')->default(-1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('suppliers_products_pivots', function (Blueprint $table) {
            //
            $table->dropColumn('measure_unit_id');
        });
    }
}
