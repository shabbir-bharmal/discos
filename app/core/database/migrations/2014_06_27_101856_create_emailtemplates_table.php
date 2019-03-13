<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailtemplatesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('emailtemplates', function($table)
        {
            $table->increments('id');
            $table->string('name');
            $table->string('view');
            $table->string('subject');
            $table->string('email_from');
            $table->string('name_from');
            $table->boolean('deleted')->default(0);
            $table->timestamps();
        });
        
        $now = date('Y-m-d H:i:s');
        
        DB::table('emailtemplates')->insert( array (
            array('name'=>'Children\'s Party Disco','view'=>'email_1','subject'=>'Cool Kids Party Quotation','email_from'=>'CoolKidsParty@coolkidsparty.com', 'name_from' => 'Nick DJ','created_at'=>$now,'updated_at'=>$now),
            array('name'=>'Primary School Disco','view'=>'email_2','subject'=>'Cool Kids Party Quotation','email_from'=>'CoolKidsParty@coolkidsparty.com', 'name_from' => 'Nick DJ','created_at'=>$now,'updated_at'=>$now),
            array('name'=>'Wedding Reception','view'=>'email_3','subject'=>'Wedding DJ Quotation','email_from'=>'TheWeddingDJ@theweddingdj.co.uk', 'name_from' => 'Nick DJ','created_at'=>$now,'updated_at'=>$now),
            array('name'=>'Others','view'=>'email_4','subject'=>'Disco Quotation','email_from'=>'DJNickBurrett@discos.co.uk', 'name_from' => 'Nick DJ', 'created_at'=>$now,'updated_at'=>$now),            
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
		Schema::drop('emailtemplates');
	}

}
