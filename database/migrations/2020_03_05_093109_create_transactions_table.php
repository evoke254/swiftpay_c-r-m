<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Transaction_Name');
            $table->double('Debit')->default(0);
            $table->double('Credit')->default(0);
            $table->string('Code');
            $table->string('Status')->default('Pending');
            $table->bigInteger('Organization')->nullable();
            $table->bigInteger('Client')->nullable();
            $table->bigInteger('Invoice')->nullable();
            $table->bigInteger('POS')->nullable();
            $table->bigInteger('Assigned_To')->nullable();
            $table->text('Description')->nullable();
            $table->string('Reference')->nullable();
            
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
        Schema::dropIfExists('transactions');
    }
}
