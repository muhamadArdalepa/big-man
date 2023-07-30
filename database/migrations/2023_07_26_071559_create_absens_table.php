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
        Schema::create('absens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->time('waktu1')->nullable();
            $table->string('foto1')->nullable();
            $table->text('koordinat1')->nullable();
            $table->text('alamat1')->nullable();
            $table->text('ket1')->nullable();
            $table->time('waktu2')->nullable();
            $table->string('foto2')->nullable();
            $table->text('koordinat2')->nullable();
            $table->text('alamat2')->nullable();
            $table->text('ket2')->nullable();
            $table->time('waktu3')->nullable();
            $table->string('foto3')->nullable();
            $table->text('koordinat3')->nullable();
            $table->text('alamat3')->nullable();
            $table->text('ket3')->nullable();
            $table->time('waktu4')->nullable();
            $table->string('foto4')->nullable();
            $table->text('koordinat4')->nullable();
            $table->text('alamat4')->nullable();
            $table->text('ket4')->nullable();
            $table->date('tanggal');
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
        Schema::dropIfExists('absens');
    }
};
