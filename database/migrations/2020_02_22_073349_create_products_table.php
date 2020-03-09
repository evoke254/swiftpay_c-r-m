<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Product_Name');
            $table->double('Selling_Price')->nullable();
            $table->integer('Units_Sold')->nullable();
            $table->integer('Available_Stock')->nullable();
            $table->double('Buying_Price')->nullable();
            $table->integer('Commission')->nullable();
            $table->integer('Actual_Stock')->nullable();
            $table->string('Image')->nullable();
            $table->bigInteger('Assigned_To')->nullable();
            $table->text('Description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
