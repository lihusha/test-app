<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('host_id');
            $table->unsignedBigInteger('guest_id');
            $table->unsignedTinyInteger('week_number');
            $table->boolean('is_finished')->default(false);
            $table->timestamps();

            $table->unique(['host_id', 'guest_id']);

            $table
                ->foreign('host_id')
                ->references('id')
                ->on('clubs')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table
                ->foreign('guest_id')
                ->references('id')
                ->on('clubs')
                ->onUpdate('cascade')
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
        Schema::dropIfExists('games');
    }
}
