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
        Schema::create('surat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('klasifikasi_surat_id');
            $table->boolean('diverifikasi')->nullable();
            $table->enum('tipe_surat', ['Surat Masuk', 'Surat Keluar', 'Surat Disposisi']);
            $table->string('nomor_surat');
            $table->string('pengirim');
            $table->string('nomor_agenda');
            $table->date('tanggal_surat');
            $table->date('tanggal_diterima');
            $table->text('keterangan')->nullable();
            $table->text('lampiran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat');
    }
};
