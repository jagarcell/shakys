<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppProdPivotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supp_prod_pivots', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('supplier_id')->default(-1);
            $table->bigInteger('product_id')->default(-1);
            $table->string('supplier_code')->default("");
            $table->string('supplier_description')->default("");
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
        Schema::dropIfExists('supp_prod_pivots');
    }
}
