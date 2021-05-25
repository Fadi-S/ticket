<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMaxReservationsToEventTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_types', function (Blueprint $table) {
            $table->unsignedInteger('max_reservations')
                ->default(1)
                ->after('name');

            $table->boolean('show')
                ->default(true);

            $table->string('url')
                ->nullable()
                ->unique();

            $table->boolean('allows_exception')
                ->default(true);

            $table->boolean('has_deacons')
                ->default(true);

            $table->string('plural_name')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_types', function (Blueprint $table) {
            $table->dropColumn('max_reservations');
        });

        Schema::table('event_types', function (Blueprint $table) {
            $table->dropColumn('show');
        });

        Schema::table('event_types', function (Blueprint $table) {
            $table->dropColumn('has_deacons');
        });

        Schema::table('event_types', function (Blueprint $table) {
            $table->dropColumn('url');
        });

        Schema::table('event_types', function (Blueprint $table) {
            $table->dropColumn('allows_exception');
        });

        Schema::table('event_types', function (Blueprint $table) {
            $table->dropColumn('plural_name');
        });
    }
}
