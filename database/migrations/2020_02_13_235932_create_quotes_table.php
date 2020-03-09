<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Quote_Subject');
            $table->dateTime('Valid_To')->nullable();
            $table->double('Amount')->default(0);
            $table->bigInteger('Opportunity')->nullable();
            $table->bigInteger('Invoice')->nullable();
            $table->bigInteger('Assigned_To');
            $table->bigInteger('Organization')->nullable();
            $table->bigInteger('Quote_Stage')->nullable();
            $table->bigInteger('Client')->nullable();
            $table->text('Description')->nullable();
            $table->json('products')->nullable();
            $table->json('productDetails')->nullable();
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
        Schema::dropIfExists('quotes');
    }
}
