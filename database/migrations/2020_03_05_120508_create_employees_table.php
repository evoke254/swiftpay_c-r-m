<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('First_Name');
            $table->string('Second_Name');
            $table->bigInteger('Department')->nullable();
            $table->string('Mobile')->nullable();
            $table->string('Email')->nullable();
            $table->dateTime('Contract_End')->nullable();
            $table->string('Image')->nullable();
            $table->dateTime('Contract_Start')->nullable();
            $table->bigInteger('User')->nullable();
            $table->string('Alternate_Mobile')->nullable();
            $table->text('Residence')->nullable();
            $table->string('Alternate_Email')->nullable();
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
        Schema::dropIfExists('employees');
    }
}
