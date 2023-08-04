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
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->text('alamat');
            $table->string('koordinat_rumah');
            $table->string('koordinat_odp');
            $table->string('serial_number');
            $table->string('SSID');
            $table->string('password');
            $table->foreignId('paket_id')
                ->constrained()
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->integer('hasil_opm_user');
            $table->integer('hasil_opm_odp');
            $table->integer('kabel_terpakai');
            $table->string('port_odp');
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
