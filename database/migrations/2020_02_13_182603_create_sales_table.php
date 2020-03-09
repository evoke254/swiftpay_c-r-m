<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Image')->nullable();
            $table->string('Product_Name');
            $table->integer('Quantity');
            $table->double('Selling_Price');
            $table->integer('Tax')->nullable();
            $table->double('Net_Price');
            $table->string('Status');
            $table->bigInteger('quotes_id');
            $table->bigInteger('invoices_id');
            $table->bigInteger('products_id');
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
        Schema::dropIfExists('sales');
    }
}
