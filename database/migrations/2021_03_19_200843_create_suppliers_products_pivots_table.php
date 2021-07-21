<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersProductsPivotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers_products_pivots', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('supplier_id')->default(-1);
            $table->bigInteger('product_units_pivot_id')->default(-1);
            $table->string('supplier_code', 50)->nullable();
            $table->string('supplier_description', 150)->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('suppliers_products_pivots');
    }
}
