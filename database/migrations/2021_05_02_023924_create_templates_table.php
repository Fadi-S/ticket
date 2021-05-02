<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')->constrained('event_types');
            $table->string('description');
            $table->unsignedInteger('day_of_week');
            $table->unsignedInteger('number_of_places');
            $table->unsignedFloat('overload')->default(0);
            $table->time('start');
            $table->time('end');
            $table->boolean('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('templates');
    }
}
