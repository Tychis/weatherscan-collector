<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class WeatherscanSetup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('xml_search_list', function (Blueprint $table) {
        $table->engine = 'Aria';
        $table->charset = 'utf8mb4';
        $table->collation = 'utf8mb4_unicode_ci';
        $table->id();
        $table->string('url', 256);
        $table->string('province', 3);
      });
      Schema::create('locations', function (Blueprint $table) {
        $table->engine = 'Aria';
        $table->charset = 'utf8mb4';
        $table->collation = 'utf8mb4_unicode_ci';
        $table->id();
        $table->string('location_name', 256);
        $table->string('province', 3);
      });
      Schema::create('alert_dict', function (Blueprint $table) {
        $table->engine = 'Aria';
        $table->charset = 'utf8mb4';
        $table->collation = 'utf8mb4_unicode_ci';
        $table->id();
        $table->string('alert_title', 256);
        $table->string('alert_type', 64)->default('Unknown');
        $table->integer('state')->comment('0 = inactive, 1 = active, 2 = informational')->default(2);
      });
      Schema::create('alert_history', function (Blueprint $table) {
        $table->engine = 'Aria';
        $table->charset = 'utf8mb4';
        $table->collation = 'utf8mb4_unicode_ci';
        $table->id();
        $table->timestamps();
        $table->integer('alert_id');
        $table->integer('location_id');
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
      Schema::drop('alert_history');
      Schema::drop('alert_dict');
      Schema::drop('locations');
      Schema::drop('xml_search_list');
    }
}
