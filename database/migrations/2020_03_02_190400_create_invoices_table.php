<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Invoice_Subject');
            $table->double('Amount')->default(0);
            $table->string('Purchase_Order')->nullable();
            $table->bigInteger('Opportunity')->nullable();
            $table->bigInteger('Quote')->nullable();
            $table->bigInteger('Assigned_To');
            $table->bigInteger('Organization')->nullable();
            $table->bigInteger('Invoice_Stage')->nullable();
            $table->bigInteger('Client')->nullable();
            $table->text('Description')->nullable();
            $table->json('products')->nullable();
            $table->json('productDetails')->nullable();
            $table->bigInteger('Transaction')->nullable();
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
        Schema::dropIfExists('invoices');
    }
}
