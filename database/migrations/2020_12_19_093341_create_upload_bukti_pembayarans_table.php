<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadBuktiPembayaransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upload_bukti_pembayarans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pemilik_rekening');
            $table->string('nomor_rekening');
            $table->string('invoice_id');
            $table->string('nama_bank');
            $table->string('foto');
            $table->integer('status');
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
        Schema::dropIfExists('upload_bukti_pembayarans');
    }
}
