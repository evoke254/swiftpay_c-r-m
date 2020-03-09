<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMpesastksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mpesastks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('PartyA');
            $table->string('PartyB');
            $table->string('Amount');
            $table->string('TransactionType');
            $table->string('MerchantRequestID')->nullable();
            $table->string('CheckoutRequestID')->nullable();
            $table->string('ResponseCode')->nullable();
            $table->string('ResponseDescription')->nullable();
            $table->string('CustomerMessage')->nullable();
            $table->string('errorCode')->nullable();
            $table->string('errorMessage')->nullable();
            $table->bigInteger('pointofsales')->nullable();
            $table->bigInteger('invoices')->nullable();
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
        Schema::dropIfExists('mpesastks');
    }
}
