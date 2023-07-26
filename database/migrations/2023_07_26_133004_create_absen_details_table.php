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
        Schema::create('absen_details', function (Blueprint $table) {
            $table->foreignId('absen_id')
                ->constrained()
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->time('waktu');
            $table->string('foto')->nullable();
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
        Schema::dropIfExists('absen_details');
    }
};
