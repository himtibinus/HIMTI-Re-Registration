<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CustomChoice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CustomChoise', function (Blueprint $table) {
            $table->increments('ID');
            $table->unsignedInteger('QuestionID');
            $table->string('InformationText');
            $table->integer('Order');
            $table->integer('IsActive')->default(1);
            $table->foreign('QuestionID')->references('ID')->on('questioninformation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('CustomChoise');
    }
}