<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conditions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('path')->unique();
            $table->unsignedInteger('priority');
            $table->boolean('required');
        });

        Schema::create('condition_type', function (Blueprint $table) {
            $table->foreignId('condition_id')->constrained('conditions');
            $table->foreignId('type_id')->constrained('event_types');
            $table->foreignId('church_id')->constrained('churches');
            $table->unsignedInteger('order');

            $table->primary(['condition_id', 'type_id', 'church_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conditions');
        Schema::dropIfExists('condition_type');
    }
}
