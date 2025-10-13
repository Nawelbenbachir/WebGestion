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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table ->string("code_cli");
            $table->string('code_comptable');
            $table->string('code_societe')->foreign()->references('code_societe')->on('societes');
            $table->string('siret',14)->nullable();
            $table->string('societe')->nullable();
            $table->string('prenom')->nullable();
            $table->string('nom')->nullable();
            $table->string('email')->unique();
            $table->string('portable1')->nullable();
            $table->string('portable2')->nullable();
            $table->string('telephone')->nullable();
            $table->string('adresse1')->nullable();
            $table->string('adresse2')->nullable();
            $table->string('complement_adresse')->nullable();
            $table->string('code_postal', 10)->nullable();
            $table->string('ville')->nullable();
            $table->string('pays')->default('France');
            $table->enum('type', ['particulier', 'artisan', 'entreprise']);
            $table->enum('civilite', ['Monsieur', 'Madame'])->nullable();   // utile si particulier ou artisan
            $table->enum('forme_juridique', ['SAS', 'SARL', 'EURL', 'SA'])->nullable();
            $table->string('iban',34)->nullable();
            $table->string('reglement')->nullable();
            $table->integer('echeance_jour')->default(30);
            $table->string("tva")->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->onUpdate('CURRENT_TIMESTAMP');
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};