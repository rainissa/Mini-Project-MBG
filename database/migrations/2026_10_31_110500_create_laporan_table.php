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
        Schema::create('laporan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelapor_id')->constrained('users')->restrictedOnDelete();
            $table->foreignId('distribusi_id')->constrained('distribusi_makanan')->restrictedOnDelete();
            $table->string('judul', 150);
            $table->text('deskripsi');
            $table->dateTime('waktu_kejadian');
            $table->unsignedInteger('jumlah_terdampak');
            $table->string('status', 50)->default('Dikirim');
            $table->string('bukti')->nullable();
            $table->text('catatan_pemantau')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};
