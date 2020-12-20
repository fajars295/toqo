<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice');
            $table->bigInteger('users_id');
            $table->bigInteger('total_transaksi');
            $table->bigInteger('alamat_id');
            $table->string('metode_pembayaran');
            $table->string('bank_id');
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
        Schema::dropIfExists('master_transactions');
    }
}
