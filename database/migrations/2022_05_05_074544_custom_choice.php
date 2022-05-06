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
        if (Schema::hasTable('sectioninformation')) Schema::table('sectioninformation', function (Blueprint $table) {
            $table->Integer('Order');
            $table->Integer('NeedValidation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('sectioninformation')) Schema::table('sectioninformation', function (Blueprint $table) {
            $table->dropColumn('Order');
            $table->dropColumn('NeedValidation');
        });
    }
}