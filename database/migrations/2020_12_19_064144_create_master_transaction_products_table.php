<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterTransactionProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_transaction_products', function (Blueprint $table) {
            $table->id();
            $table->string('invoice');
            $table->string('kurir');
            $table->bigInteger('harga_kurir');
            $table->bigInteger('master_transactions_id');
            $table->bigInteger('users_id');
            $table->bigInteger('status');
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
        Schema::dropIfExists('master_transaction_products');
    }
}
