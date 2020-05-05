<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CurrentConditions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('current_conditions', function (Blueprint $table) {
          $table->engine = 'Aria';
          $table->charset = 'utf8mb4';
          $table->collation = 'utf8mb4_unicode_ci';
          $table->id();
          $table->timestamps();
          $table->integer('alert_id');
          $table->integer('location_id')->unique();;
          $table->dateTime('issue_datetime', 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('current_conditions');
    }
}
