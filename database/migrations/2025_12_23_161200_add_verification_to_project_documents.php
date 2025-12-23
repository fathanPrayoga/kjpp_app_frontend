<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_documents', function (Blueprint $table) {
            if (!Schema::hasColumn('project_documents', 'status')) {
                $table->string('status')->default('pending')->after('file_path');
            }
            if (!Schema::hasColumn('project_documents', 'notes')) {
                $table->text('notes')->nullable()->after('status');
            }
            if (!Schema::hasColumn('project_documents', 'verified_by')) {
                $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete()->after('notes');
            }
            if (!Schema::hasColumn('project_documents', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('verified_by');
            }
        });
    }

    public function down(): void
    {
        Schema::table('project_documents', function (Blueprint $table) {
            if (Schema::hasColumn('project_documents', 'verified_at')) {
                $table->dropColumn('verified_at');
            }
            if (Schema::hasColumn('project_documents', 'verified_by')) {
                $table->dropForeign(['verified_by']);
                $table->dropColumn('verified_by');
            }
            if (Schema::hasColumn('project_documents', 'notes')) {
                $table->dropColumn('notes');
            }
            if (Schema::hasColumn('project_documents', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
