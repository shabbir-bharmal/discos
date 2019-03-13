<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('users', function($table)
        {
            $table->increments('id');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('name');
            $table->boolean('deleted')->default(0);
            $table->string('remember_token')->nullable();
            $table->timestamps();
        });
        
        $now = date('Y-m-d H:i:s');
        
        DB::table('users')->insert( array (
            'username' => 'admin',
            'name' => 'Nick',
            'password'=> Hash::make('Quartley246'),
            'created_at'=>$now, 'updated_at'=>$now
            )
        );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
