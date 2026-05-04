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
    Schema::table('reglements', function (Blueprint $table) {
        if (Schema::hasColumn('reglements', 'document_id')) {
            $table->dropForeign(['document_id']);
            $table->dropColumn('document_id');
        }
    });
}

public function down(): void
{
    Schema::table('reglements', function (Blueprint $table) {
        $table->foreignId('document_id')->nullable()
              ->constrained('en_tete_documents')
              ->onDelete('cascade');
    });
}
};
