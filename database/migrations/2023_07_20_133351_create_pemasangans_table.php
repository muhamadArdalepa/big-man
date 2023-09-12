<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemasangans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')
                ->constrained('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->char('nik');
            $table->string('foto_ktp');
            $table->string('foto_rumah');
            $table->string('foto_modem')->nullable();
            $table->string('foto_letak_modem')->nullable();
            $table->string('foto_opm_user')->nullable();
            $table->string('foto_opm_odp')->nullable();
            $table->text('alamat');
            $table->string('koordinat_rumah');
            $table->string('koordinat_odp')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('SSID')->nullable();
            $table->string('password')->nullable();
            $table->foreignId('paket_id')
                ->constrained()
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->integer('hasil_opm_user')->nullable();
            $table->integer('hasil_opm_odp')->nullable();
            $table->integer('kabel_terpakai')->nullable();
            $table->string('port_odp')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->text('ket')->nullable();
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
        Schema::dropIfExists('pemasangans');
    }
};
