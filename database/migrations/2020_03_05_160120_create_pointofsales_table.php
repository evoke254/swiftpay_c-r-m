<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePointofsalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pointofsales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Subject');
            $table->double('Amount')->default(0);
            $table->string('Status')->nullable();
            $table->string('Pay_Via')->nullable();
            $table->bigInteger('Assigned_To');
            $table->bigInteger('Organization')->nullable();
            $table->bigInteger('Client')->nullable();
            $table->text('Description')->nullable();
            $table->json('products')->nullable();
            $table->string('Reference')->nullable();
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
        Schema::dropIfExists('pointofsales');
    }
}
