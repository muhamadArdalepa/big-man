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
        Schema::create('pekerjaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tim_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreignId('wilayah_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreignId('jenis_pekerjaan_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreignId('laporan_id')
                ->nullable()
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreignId('pemasangan_id')
                ->nullable()
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->integer('poin');
            $table->string('detail')->nullable();
            $table->enum('status',['sedang diproses','ditunda','selesai','dibatalkan']);
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
        Schema::dropIfExists('pekerjaans');
    }
};
