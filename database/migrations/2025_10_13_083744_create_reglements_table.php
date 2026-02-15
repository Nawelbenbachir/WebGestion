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
        Schema::create('reglements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('societe_id')->constrained('societes')->onDelete('cascade');
            $table->string('numero_reglement');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->onUpdate('CURRENT_TIMESTAMP');
            $table->unsignedBigInteger('document_id');
            $table->foreign('document_id')->references('id')->on('en_tete_documents')->onDelete('cascade');
            $table->date('date_reglement')->nullable();
            $table->decimal('montant', 10, 2)->nullable();
            $table->string('mode_reglement')->nullable();
            $table->string('reference')->nullable();
            $table->text('commentaire')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reglements');
    }
};

