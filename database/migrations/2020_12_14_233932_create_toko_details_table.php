<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTokoDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('toko_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('users_id');
            $table->string('logo')->nullable();
            $table->string('foto_ktp');
            $table->string('foto_diri');
            $table->string('alamat_toko');
            $table->string('alamat_pemilik_toko');
            $table->string('nomor_toko');
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
        Schema::dropIfExists('toko_details');
    }
}
