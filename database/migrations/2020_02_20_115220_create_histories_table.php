<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('operation');
            $table->string('moduleName');
            $table->string('refModule')->nullable();
            $table->string('column')->nullable();
            $table->string('prev_value')->nullable();
            $table->string('updated_value')->nullable();
            $table->bigInteger('user_id'); 
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
        Schema::dropIfExists('histories');
    }
}
