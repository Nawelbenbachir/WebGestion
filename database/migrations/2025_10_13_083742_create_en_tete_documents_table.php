
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
        Schema::create('en_tete_documents', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->onUpdate('CURRENT_TIMESTAMP');
            $table->unsignedBigInteger('societe_id');
            $table->foreign('societe_id')->references('id')->on('societes')->onDelete('cascade');

            $table->string('code_document')->unique();
            $table->enum('type_document', ['F', 'D', 'A'])->nullable();
            $table->date('date_document')->nullable();
            
            
            $table->decimal('total_ht', 10, 2)->nullable();
            $table->decimal('total_tva', 10, 2)->nullable();
            $table->decimal('total_ttc', 10, 2)->nullable();
            $table->decimal('solde', 10, 2)->nullable();

            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');

            $table->string('client_nom')->nullable();
            $table->string('logo')->nullable();
            $table->string('adresse')->nullable();
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->date('date_echeance')->nullable();
            $table->text('commentaire')->nullable();
            $table->enum('statut', ['brouillon', 'envoye', 'paye'])->default('brouillon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('en_tete_documents');
    }
};