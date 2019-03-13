<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentSetPackageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment_set_package', function (Blueprint $table) {
            $table->integer('equipment_set_id')->unsigned();
            $table->integer('package_id')->unsigned();
            $table->foreign('equipment_set_id')->references('id')->on('equipment_sets')->onDelete('cascade');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('equipment_set_package');
    }
}
