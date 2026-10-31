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
        Schema::create('penerusan_laporan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_id')->constrained('laporan')->restrictedOnDelete();
            $table->string('jenis_laporan', 50);
            $table->string('nama_penerima');
            $table->text('catatan_penerusan')->nullable();
            $table->foreignId('dicatat_oleh')->constrained('users')->restrictedOnDelete();
            $table->dateTime('waktu_diteruskan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerusan_laporan');
    }
};
