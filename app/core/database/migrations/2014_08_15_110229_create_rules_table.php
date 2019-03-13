<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRulesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('rules', function($table)
        {
            $table->increments('id');
            $table->string('name');
            $table->date('date_from');
            $table->date('date_to');
            $table->integer('package_id');
            $table->boolean('deleted')->default(0); //todo: use softDeletes()
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
		Schema::drop('rules');
	}

}
