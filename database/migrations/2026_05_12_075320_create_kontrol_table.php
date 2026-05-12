<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kontrol', function (Blueprint $table) {
            $table->id();
            $table->string('noSuratKontrol', 50);
            $table->string('jnsPelayanan', 20);
            $table->string('jnsKontrol', 10);
            $table->string('namaJnsKontrol', 20);
            $table->date('tglRencanaKontrol');
            $table->date('tglTerbitKontrol');
            $table->string('noSepAsalKontrol', 50)->nullable();
            $table->string('poliAsal', 50)->nullable();
            $table->string('namaPoliAsal', 50)->nullable();
            $table->string('poliTujuan', 50)->nullable();
            $table->string('namaPoliTujuan', 100);
            $table->date('tglSEP')->nullable();
            $table->string('kodeDokter', 20);
            $table->string('namaDokter', 100);
            $table->string('noKartu', 50);
            $table->string('nama', 100);
            $table->enum('terbitSEP', ['Sudah', 'Belum'])->default('Belum');
            $table->string('penerbit', 100)->nullable();
            $table->string('codeAsal', 100)->nullable();
            $table->string('namaAsal', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kontrol');
    }
};