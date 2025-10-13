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
    Schema::create('produits', function (Blueprint $table) {
          
        $table->id();
        $table->string('code_societe')->foreign()->references('code_societe')->on('societes');
        $table->string('code_produit')->unique();
        $table->string('code_comptable')->default('70001');
        $table->string('description')->nullable();
        $table->decimal('prix_ht', 10, 2)->nullable();
        $table->decimal('tva', 5, 2)->default(20)->nullable();
        $table->integer('qt_stock')->nullable();
        $table->string('categorie')->nullable();
        $table->timestamp('created_at')->useCurrent();
        $table->timestamp('updated_at')->useCurrent()->onUpdate('CURRENT_TIMESTAMP');
        
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
