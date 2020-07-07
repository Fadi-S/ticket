<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->integer("event_id");
            $table->integer("user_id");
            $table->timestamp("reserved_at");
            $table->string("secret");
            $table->timestamps();
        });

        Schema::create('reservation_user', function (Blueprint $table) {
            $table->integer("reservation_id");
            $table->integer("user_id");

            $table->primary(["reservation_id", "user_id"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('reservation_user');
    }
}
