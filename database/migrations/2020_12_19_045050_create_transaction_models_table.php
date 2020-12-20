<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_models', function (Blueprint $table) {
            $table->id();
            $table->string('product');
            $table->string('jumlah');
            $table->bigInteger('harga_product');
            $table->bigInteger('status');
            $table->bigInteger('users_id');
            $table->string('nomor_invoice');
            $table->bigInteger('master_transactions_id');
            $table->bigInteger('master_transactions_products_id');
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
        Schema::dropIfExists('transaction_models');
    }
}
