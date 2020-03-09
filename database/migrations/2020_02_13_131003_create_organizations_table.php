<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Organization_Name');
            $table->bigInteger('Account_Number')->nullable();
            $table->string('Mobile')->nullable();
            $table->string('Location')->nullable();
            $table->bigInteger('Assigned_To')->nullable();
            $table->string('Phone')->nullable();
            $table->string('Email_1')->nullable();
            $table->string('Email_2')->nullable();
            $table->string('Address')->nullable();
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
        Schema::dropIfExists('organizations');
    }
}
