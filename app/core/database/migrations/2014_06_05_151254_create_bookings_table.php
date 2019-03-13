<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('bookings', function($table)
        {
            $table->increments('id');
            $table->string('email_token')->nullable();
            $table->integer('client_id');
            $table->integer('package_id');
            $table->date('date_booked');
            $table->date('date');
            $table->time('start_time');
            $table->time('finish_time');
            $table->string('venue_name');
            $table->string('venue_address1')->nullable();
            $table->string('venue_address2')->nullable();
            $table->string('venue_address3')->nullable();
            $table->string('venue_postcode');
            $table->string('event_occasion');
            /* todo: $table->string('event_details'); json*/
            $table->string('groom_firstname')->nullable();
            $table->string('groom_surname')->nullable();
            $table->string('bride_firstname')->nullable();
            $table->string('birthday_name')->nullable();
            $table->string('birthday_age')->nullable();
            $table->decimal('deposit_requested', 10, 2);
            $table->date('deposit_paid')->nullable();
            $table->decimal('deposit_amount', 10, 2)->nullable();
            $table->string('deposit_payment_method')->nullable();
            $table->decimal('balance_requested', 10, 2);
            $table->date('balance_paid')->nullable();
            $table->decimal('balance_amount', 10, 2)->nullable();
            $table->string('balance_payment_method')->nullable();
            $table->decimal('total_cost', 10, 2);
            $table->integer('setup_equipment_time')->nullable();
            $table->string('staff')->nullable();
            $table->string('status');
            $table->string('ref_no')->nullable();
            $table->string('notes')->nullable();
            $table->boolean('deleted')->default(0);
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
		Schema::drop('bookings');
	}

}
