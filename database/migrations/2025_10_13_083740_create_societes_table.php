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
        Schema::create('societes', function (Blueprint $table) {
            $table->id();
            $table->string('code_societe')->unique();
            $table->string('nom_societe');
            $table->string('siret',14)->nullable();
            $table->string('email')->unique();
            $table->string('telephone')->nullable();
            $table->string('adresse1')->nullable();
            $table->string('adresse2')->nullable();
            $table->string('complement_adresse')->nullable();
            $table->string('code_postal', 10)->nullable();
            $table->string('ville')->nullable();
            $table->string('pays')->default('France');
            $table->string('iban',34)->nullable();
            $table->string('swift',11)->nullable();
            $table->string('tva')->nullable();
            $table->string('logo')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->onUpdate('CURRENT_TIMESTAMP');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('societes');
    }
};
