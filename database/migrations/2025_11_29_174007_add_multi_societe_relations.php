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
        //  Ajout de la clé propriétaire à la table societes
        Schema::table('societes', function (Blueprint $table) {
            $table->foreignId('proprietaire_id')->nullable()->after('id')->constrained('users')->onDelete('set null');
        });

        //  Création de la table de pivot 
        Schema::create('societe_user', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('societe_id')->constrained()->onDelete('cascade');
            $table->string('role_societe')->default('user'); 
            $table->primary(['user_id', 'societe_id']);
            $table->timestamps();
        });

        //  Ajout de la préférence utilisateur (dernière société active)
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('last_active_societe_id')->nullable()->after('password')->constrained('societes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //  Suppression des clés étrangères de societes
        Schema::table('societes', function (Blueprint $table) {
            $table->dropForeign(['proprietaire_id']);
            $table->dropColumn('proprietaire_id');
        });

        //  Suppression de la table de pivot
        Schema::dropIfExists('societe_user');
        
        //  Suppression de la clé étrangère dans users
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['last_active_societe_id']);
            $table->dropColumn('last_active_societe_id');
        });
    }
};