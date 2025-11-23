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
        Schema::create('parametres', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('derniere_societe')->foreign()->references('id')->on('societes')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->onUpdate('CURRENT_TIMESTAMP');
        });
    }

//         DB::unprepared('
//        CREATE TRIGGER trig_after_insert_parametre
// AFTER INSERT ON parametres
// FOR EACH ROW
// BEGIN
//     DELETE FROM parametres WHERE id != NEW.id;
// END;
//         ');
//     

//         CREATE TRIGGER trig_verif_unique_parametre
// BEFORE INSERT ON parametres
// FOR EACH ROW
// BEGIN
//     IF (SELECT COUNT(*) FROM parametres) >= 1 THEN
//         SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "Une seule ligne est autorisée dans la table parametres";
//     END IF;
// END;
    


        // DB::unprepared('DROP TRIGGER IF EXISTS before_insert_parametres;');
        // DB::unprepared('
        //     CREATE TRIGGER before_insert_parametres
        //     BEFORE INSERT ON parametres
        //     FOR EACH ROW
        //     BEGIN
        //         DECLARE last_id BIGINT;
        //         SELECT id INTO last_id FROM parametres ORDER BY id DESC LIMIT 1;
        //         IF last_id IS NOT NULL THEN
        //             DELETE FROM parametres WHERE id = last_id;
        //         END IF;
        //     END;
        //     ');
        // DB::unprepared('

        //     CREATE TRIGGER before_insert_parametres
        //     BEFORE INSERT ON parametres
        //     FOR EACH ROW
        //     BEGIN
        //         DELETE FROM parametres;
        //     END;
        // ');
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //DB::unprepared('DROP TRIGGER IF EXISTS trig_verif_unique_parametre');
       // DB::unprepared('DROP TRIGGER IF EXISTS trig_keep_one_parametre');
        Schema::dropIfExists('parametres');
    }
};
