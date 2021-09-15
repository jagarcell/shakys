<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductStopColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supp_prod_pivots', function (Blueprint $table) {
            $table->integer('location_stop')->default(-1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supp_prod_pivots', function (Blueprint $table) {
            //
            $table->dropColumn('location_stop');
        });
    }
}
