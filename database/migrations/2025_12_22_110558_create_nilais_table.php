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
        Schema::create('nilais', function (Blueprint $table) {
            $table->id();

            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            
            $table->string('status_penilaian')->default('Belum Dinilai');
            
            $table->bigInteger('nilai_pasar_final')->nullable();
            $table->bigInteger('nilai_tanah')->nullable();
            $table->bigInteger('nilai_indikasi_dari_pasar')->nullable();
            $table->bigInteger('nilai_indikasi_dari_biaya')->nullable();
            $table->bigInteger('nilai_likuidasi')->nullable();
            $table->bigInteger('nilai_bangunan')->nullable();
            $table->bigInteger('nilai_per_m2_tanah')->nullable();
            $table->bigInteger('nilai_per_m2_bangunan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilais');
    }
};
