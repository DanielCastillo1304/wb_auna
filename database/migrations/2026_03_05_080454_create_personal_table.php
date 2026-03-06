<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalTable extends Migration
{
    public function up()
    {
        Schema::create('maintenance.personal', function (Blueprint $table) {

            $table->bigIncrements('codpersonal');

            /*
            |--------------------------------------------------------------------------
            | Datos personales
            |--------------------------------------------------------------------------
            */

            $table->string('dni',15)->nullable();
            $table->string('usr_sfsf',50)->nullable();
            $table->string('ape_nom',255);
            $table->string('sexo',20)->nullable();
            $table->string('correo',255)->nullable();
            $table->string('telefono',50)->nullable();
            $table->date('fec_ing')->index();

            /*
            |--------------------------------------------------------------------------
            | Datos laborales
            |--------------------------------------------------------------------------
            */

            $table->string('tipo_contrato',150)->nullable();
            $table->string('exclusividad',100)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Organización
            |--------------------------------------------------------------------------
            */

            $table->string('cod_sociedad',50)->nullable();
            $table->string('soc',255)->nullable()->index();
            $table->string('alcance',255)->nullable();
            $table->string('negocio_atendido',255)->nullable();

            $table->string('cod_n1',50)->nullable();
            $table->string('n1',255)->nullable();

            $table->string('cod_n2',50)->nullable();
            $table->string('n2',255)->nullable();

            $table->string('cod_n3',50)->nullable();
            $table->string('n3',255)->nullable();

            $table->string('cod_n4',50)->nullable();
            $table->string('area_n4',255)->nullable()->index();

            $table->string('cod_n5',50)->nullable();
            $table->string('n5',255)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Cargo
            |--------------------------------------------------------------------------
            */

            $table->string('cargo',255)->nullable()->index();
            $table->string('cod_funcion',50)->nullable();
            $table->string('cat_ocup',255)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Costos
            |--------------------------------------------------------------------------
            */

            $table->string('ccosto',50)->nullable()->index();
            $table->string('desc_ccosto',255)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Ubicación
            |--------------------------------------------------------------------------
            */

            $table->string('cod_sede',50)->nullable();
            $table->string('sede',255)->nullable()->index();

            /*
            |--------------------------------------------------------------------------
            | Jefatura directa
            |--------------------------------------------------------------------------
            */

            $table->string('posicion_jefe',100)->nullable();
            $table->string('cargo_jef',255)->nullable();
            $table->string('nom_jef',255)->nullable()->index();

            /*
            |--------------------------------------------------------------------------
            | Información RRHH
            |--------------------------------------------------------------------------
            */

            $table->string('division_personal',255)->nullable();
            $table->string('desc_division_personal',255)->nullable();
            $table->string('desc_area_personal',255)->nullable();
            $table->string('regimen_laboral',150)->nullable();
            $table->string('relacion_laboral',150)->nullable();

            /*
            |--------------------------------------------------------------------------
            | Control
            |--------------------------------------------------------------------------
            */

            $table->timestamps();
            $table->softDeletes();

        });
    }

    public function down()
    {
        Schema::dropIfExists('maintenance.personal');
    }
}