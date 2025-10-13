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
    {Schema::create('ligne_documents', function (Blueprint $table) {
    $table->id();
    $table->timestamps();

    // Relation avec l'en-tête du document
    $table->unsignedBigInteger('document_id');
    $table->foreign('document_id')
        ->references('id')->on('en_tete_documents')
        ->onDelete('cascade');

    // Relation avec le produit
    $table->unsignedBigInteger('produit_id')->nullable();
    $table->foreign('produit_id')
        ->references('id')->on('produits')
        ->nullOnDelete();

    // Infos produit/ligne
    $table->string('designation');
    $table->decimal('prix_unitaire_ht', 10, 2);
    $table->decimal('taux_tva', 5, 2)->default(20.00);
    $table->integer('quantite')->default(1);

    // Totaux
    $table->decimal('total_ht', 10, 2);
    $table->decimal('total_tva', 10, 2);
    $table->decimal('total_ttc', 10, 2);

    //  Commentaire pour la ligne
    $table->text('commentaire')->nullable();
});

       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ligne_documents');
    }
};
