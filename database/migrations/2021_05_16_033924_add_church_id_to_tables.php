<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChurchIdToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $addColumn = fn (Blueprint $table) =>

            $table->foreignId('church_id')->nullable();

        Schema::table('users', $addColumn);

        Schema::table('events', $addColumn);

        Schema::table('templates', $addColumn);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $dropColumn = fn (Blueprint $table) => $table->dropColumn('church_id');

        Schema::table('users', $dropColumn);

        Schema::table('events', $dropColumn);

        Schema::table('templates', $dropColumn);
    }
}
