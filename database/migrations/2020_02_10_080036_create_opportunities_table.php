<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpportunitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opportunities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Opportunity_Name');
            $table->integer('Contract_in_months')->nullable();
            $table->double('Monthly_Cost')->nullable();
            $table->double('Total_Revenue')->nullable();
            $table->date('Expected_Close_Date')->nullable();
            $table->integer('Probability_as_a_percentage')->nullable();
            $table->bigInteger('Lead_Source')->nullable();
            $table->bigInteger('Organization')->nullable();
            $table->bigInteger('Sales_Stage')->nullable();
            $table->bigInteger('Client_Name')->nullable();
            $table->bigInteger('Assigned_To')->nullable();
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
        Schema::dropIfExists('opportunities');
    }
}
