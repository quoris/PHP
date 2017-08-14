<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('values', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('page_id')->unsigned(); // обязательно использовать unsigned() перед созданием вторичного ключа
            $table->integer('parameter_id')->unsigned(); // обязательно использовать unsigned() перед созданием вторичного ключа;
            $table->string('status');
            $table->integer('value');
            $table->string('time');
            $table->timestamps();
        });

        Schema::table('values', function ($table) {
            $table->foreign('page_id')->references('id')->on('pages');
        });

        Schema::table('values', function ($table) {
            $table->foreign('parameter_id')->references('id')->on('parameters');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('values');
    }
}
