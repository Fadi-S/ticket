<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('periods', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->date('start')->index();
            $table->date('end')->index();
            $table->timestamps();
        });

        Schema::create('period_type', function (Blueprint $table) {
            $table->foreignId('period_id');
            $table->foreignId('type_id');
            $table->unsignedInteger('max_reservations');

            $table->primary(['period_id', 'type_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('periods');
    }
}
