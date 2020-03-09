<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendars', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('location');
            $table->string('details')->nullable();
            $table->dateTime('start');
            $table->dateTime('end');
            $table->json('attendees')->nullable();
            $table->string('module_name');
            $table->bigInteger('User_id'); 
            $table->bigInteger('object_id');
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
        Schema::dropIfExists('calendars');
    }
}

