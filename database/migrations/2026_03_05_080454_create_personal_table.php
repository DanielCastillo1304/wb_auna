<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maintenance.personal', function (Blueprint $table) {
            $table->bigIncrements('codpersonal');

            // Datos personales
            $table->string('dni', 8)->unique();
            $table->string('usr_sfsf', 8)->nullable();
            $table->string('ape_nom', 150);
            $table->string('correo', 150)->unique();
            $table->date('fec_ing');

            // Datos organizacionales
            $table->string('soc', 100);
            $table->string('area_n4', 100)->nullable();
            $table->string('cargo', 100);
            $table->string('cat_ocup', 100)->nullable();
            $table->string('ccosto', 50)->nullable();
            $table->string('sede', 100)->nullable();

            // Jefatura directa
            $table->string('cargo_jef', 100)->nullable();
            $table->string('nom_jef', 150)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('maintenance.personal');
    }
}
