<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TambahTabelProductType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('products', function (Blueprint $table) {
            $table->string('type')->nullable();
            $table->bigInteger('status_ongkir')->nullable();
            $table->bigInteger('casback')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('type')->nullable();
            $table->dropColumn('status_ongkir')->nullable();
            $table->dropColumn('casback')->nullable();
        });
    }
}
