<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Contact_Name');
            $table->bigInteger('Account_Number')->nullable();
            $table->string('Mobile')->nullable();
            $table->bigInteger('Organization')->nullable();
            $table->bigInteger('Assigned_To')->nullable();
            $table->string('Phone')->nullable();
            $table->string('Email_1')->nullable();
            $table->string('Email_2')->nullable();
            $table->string('Address')->nullable();
            $table->text('Location')->nullable();
            $table->text('Description')->nullable();
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
        Schema::dropIfExists('contacts');
    }
}
