<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{DB, Schema};

class ReRegister extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('QuestionType', function (Blueprint $table) {
            $table->increments('ID');
            $table->string('QuestionTypeName');
            $table->integer('IsActive')->default(1);
        });
        Schema::create('QuestionInformation', function (Blueprint $table) {
            $table->increments('ID');
            $table->string('QuestionText');
            $table->string('QuestionDescription');
            $table->integer('IsNull');
            $table->unsignedInteger('QuestionType');
            $table->integer('IsActive')->default(1);
            $table->foreign('QuestionType')->references('ID')->on('QuestionType');
        });
        Schema::create('SectionInformation', function (Blueprint $table) {
            $table->increments('ID');
            $table->string('SectionName');
            $table->string('SectionDescription');
            $table->integer('IsActive')->default(1);
        });
        Schema::create('QuestionSection', function (Blueprint $table) {
            $table->increments('ID');
            $table->year('Year');
            $table->integer('Quartil');
            $table->unsignedInteger('QuestionID');
            $table->unsignedInteger('SectionID');
            $table->integer('Order');
            $table->integer('IsActive')->default(1);
            $table->foreign('QuestionID')->references('ID')->on('QuestionInformation');
            $table->foreign('SectionID')->references('ID')->on('SectionInformation');
        });
        Schema::create('DataInformation', function (Blueprint $table) {
            $table->increments('ID');
            $table->unsignedBigInteger('UserID');
            $table->year('Year');
            $table->integer('Quartil');
            $table->unsignedInteger('QuestionId');
            $table->string('AnswerText')->nullable();
            $table->Date('AnswerDate')->nullable();
            $table->string('AnswerFileName')->nullable();
            $table->foreign('QuestionId')->references('ID')->on('QuestionInformation');
        });
        Schema::create('QuartilInformation', function (Blueprint $table) {
            $table->increments('ID');
            $table->year('Year');
            $table->integer('Quartil');
            $table->integer('IsActive')->default(1);
        });
        Schema::create('AdminRegistration', function (Blueprint $table) {
            $table->increments('ID');
            $table->unsignedBigInteger('UserID');
            $table->year('Year');
            $table->integer('IsActive')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('DataInformation');
        Schema::dropIfExists('QuestionSection');
        Schema::dropIfExists('QuestionInformation');
        Schema::dropIfExists('SectionInformation');
        Schema::dropIfExists('QuestionType');
        Schema::dropIfExists('AdminRegistration');
    }
}