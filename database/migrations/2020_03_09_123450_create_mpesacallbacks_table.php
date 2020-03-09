<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMpesacallbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mpesacallbacks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('amount')->nullable();
            $table->string('number')->nullable();
            $table->string('resultCode')->nullable();
            $table->string('merchantID')->nullable();
            $table->string('checkoutID')->nullable();
            $table->string('receipt')->nullable();
            $table->string('datetime')->nullable();
            $table->string('resultdesc')->nullable();
            $table->string('resultdcode')->nullable();
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
        Schema::dropIfExists('mpesacallbacks');
    }
}
