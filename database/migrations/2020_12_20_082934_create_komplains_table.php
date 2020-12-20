<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKomplainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('komplains', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pembelian_id');
            $table->bigInteger('penjual_id');
            $table->text('masalah_product');
            $table->text('type_product');
            $table->string('foto');
            $table->bigInteger('status');
            $table->bigInteger('users_id');
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
        Schema::dropIfExists('komplains');
    }
}
