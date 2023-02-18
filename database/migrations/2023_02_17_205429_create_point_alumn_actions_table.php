<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePointAlumnActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('point_alumn_actions', function (Blueprint $table) {
            $table->id();
            $table->integer('points')->nullable();
            $table->unsignedBigInteger('user_send_id');
            $table->unsignedBigInteger('user_recept_id');
            $table->unsignedBigInteger('action_id');

            $table->foreign('user_send_id')
            ->references('id')->on('users')
            ->onDelete('cascade');
            $table->foreign('user_recept_id')
            ->references('id')->on('users')
            ->onDelete('cascade');
            $table->foreign('action_id')
            ->references('id')->on('actions')
            ->onDelete('cascade');
            $table->timestamps();
            $table->rememberToken();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('point_alumn_actions');
    }
}
