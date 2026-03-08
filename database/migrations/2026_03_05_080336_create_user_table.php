<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('security.user', function (Blueprint $table) {
            $table->bigIncrements('coduser');
            $table->unsignedBigInteger('codprofile');
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreings
            $table->foreign('codprofile')
                ->references('codprofile')
                ->on('security.profile')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
}
