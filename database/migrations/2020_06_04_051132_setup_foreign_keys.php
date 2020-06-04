<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetupForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->foreign('county_id')->references('id')->on('counties');
        });

        Schema::table('alert_history', function (Blueprint $table) {
            $table->foreign('alert_id')->references('id')->on('alert_dict');
            $table->foreign('location_id')->references('id')->on('locations');
        });

        Schema::table('current_conditions', function (Blueprint $table) {
            $table->foreign('alert_id')->references('id')->on('alert_dict');
            $table->foreign('location_id')->references('id')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropForeign('locations_county_id_foreign');
        });

        Schema::table('alert_history', function (Blueprint $table) {
            $table->dropForeign('alert_history_alert_id_foreign');
            $table->dropForeign('alert_history_location_id_foreign');
        });

        Schema::table('current_conditions', function (Blueprint $table) {
            $table->dropForeign('current_conditions_alert_id_foreign');
            $table->dropForeign('current_conditions_location_id_foreign');
        });
    }
}
