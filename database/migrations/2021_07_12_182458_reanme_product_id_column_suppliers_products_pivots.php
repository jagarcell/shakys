<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReanmeProductIdColumnSuppliersProductsPivots extends Migration
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
            $table->renameColumn('product_id', 'product_units_pivot_id');
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
            $table->renameColumn('product_units_pivot_id', 'product_id');

        });
    }
}
