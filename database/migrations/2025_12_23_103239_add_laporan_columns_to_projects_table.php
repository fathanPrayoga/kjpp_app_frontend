<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Menambahkan kolom baru setelah kolom 'status' atau 'deskripsi'
            $table->string('asal_instansi')->nullable()->after('status');
            $table->date('tanggal_mulai')->nullable()->after('asal_instansi');
            $table->string('dokumen')->nullable()->after('tanggal_mulai');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['asal_instansi', 'tanggal_mulai', 'dokumen']);
        });
    }
};
