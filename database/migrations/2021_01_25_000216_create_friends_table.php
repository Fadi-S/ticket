<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFriendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('friendships', function (Blueprint $table) {
            $table->id();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('friendship_user', function (Blueprint $table) {
            $table->foreignId('user_id');
            $table->foreignId('friendship_id');

            $table->primary(['user_id', 'friendship_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('friendships');

        Schema::dropIfExists('friendship_user');
    }
}
