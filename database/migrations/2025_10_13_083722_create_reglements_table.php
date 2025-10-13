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
            $table->timestamps();
            $table->unsignedBigInteger('reglement_id');
            $table->foreign('document_id')->references('id')->on('en_tete_documents')->onDelete('cascade');
            $table->date('date_reglement')->nullable();
            $table->decimal('montant', 10, 2)->nullable();
            $table->string('mode_reglement')->nullable();
            $table->string('reference')->nullable();
            $table->text('commentaire')->nullable();
           $table->foreign('type_document')->references('type_document')->on('en_tete_documents')->onDelete('cascade');

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

