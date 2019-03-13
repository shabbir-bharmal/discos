<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackagesTable extends Migration {


	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('packages', function($table)
        {
            $table->increments('id');
            $table->string('name');
            $table->string('day');
            $table->time('start_time');
            $table->time('finish_time');
            $table->float('min_price');
            $table->float('max_price');
            $table->float('hours_inc');
            $table->float('overtime_cost');
            $table->integer('overtime_interval');
            $table->integer('free_travel');
            $table->float('travel_cost');
            $table->integer('travel_interval');
            $table->integer('setup_time')->nullable();
            $table->string('email_template_id');
            $table->boolean('deleted')->default(0);
            $table->timestamps();
        });
        
        $now = date('Y-m-d H:i:s');
        
        DB::table('packages')->insert( array (
            array('id'=>1,'name'=>'Children\'s Party Disco', 'day'=>'Mon,Tue,Wed,Thu', 'start_time'=>'08:00', 'finish_time'=>'04:00', 'min_price'=>120, 'hours_inc'=>2,'overtime_cost'=>15,'overtime_interval'=>'30', 'max_price'=>200, 'free_travel'=>'30', 'travel_cost'=>15,'travel_interval'=>'30','email_template_id'=>1, 'setup_time' => '45', 'created_at'=>$now, 'updated_at'=>$now),
            array('id'=>2,'name'=>'Children\'s Party Disco', 'day'=>'Fri', 'start_time'=>'08:00', 'finish_time'=>'16:30', 'min_price'=>120, 'hours_inc'=>2,'overtime_cost'=>15,'overtime_interval'=>'30', 'max_price'=>200, 'free_travel'=>'30', 'travel_cost'=>15,'travel_interval'=>'30','email_template_id'=>1, 'setup_time' => '45','created_at'=>$now, 'updated_at'=>$now),
            array('id'=>3,'name'=>'Children\'s Party Disco', 'day'=>'Fri', 'start_time'=>'16:30', 'finish_time'=>'04:00', 'min_price'=>150, 'hours_inc'=>2,'overtime_cost'=>15,'overtime_interval'=>'30', 'max_price'=>200, 'free_travel'=>'30', 'travel_cost'=>15,'travel_interval'=>'30','email_template_id'=>1,'setup_time' => '45', 'created_at'=>$now, 'updated_at'=>$now),
            array('id'=>4,'name'=>'Children\'s Party Disco', 'day'=>'Sat', 'start_time'=>'08:00', 'finish_time'=>'16:30', 'min_price'=>120, 'hours_inc'=>2,'overtime_cost'=>15,'overtime_interval'=>'30', 'max_price'=>200, 'free_travel'=>'30', 'travel_cost'=>15,'travel_interval'=>'30','email_template_id'=>1, 'setup_time' => '45','created_at'=>$now, 'updated_at'=>$now),
            array('id'=>5,'name'=>'Children\'s Party Disco', 'day'=>'Sat', 'start_time'=>'16:30', 'finish_time'=>'04:00', 'min_price'=>175, 'hours_inc'=>2,'overtime_cost'=>15,'overtime_interval'=>'30', 'max_price'=>200, 'free_travel'=>'30', 'travel_cost'=>15,'travel_interval'=>'30','email_template_id'=>1, 'setup_time' => '45','created_at'=>$now, 'updated_at'=>$now),
            array('id'=>6,'name'=>'Children\'s Party Disco', 'day'=>'Sun', 'start_time'=>'08:00', 'finish_time'=>'04:00', 'min_price'=>120, 'hours_inc'=>2,'overtime_cost'=>15,'overtime_interval'=>'30', 'max_price'=>200, 'free_travel'=>'30', 'travel_cost'=>15,'travel_interval'=>'30','email_template_id'=>1,'setup_time' => '45', 'created_at'=>$now, 'updated_at'=>$now),
                
            array('id'=>7,'name'=>'Primary School Disco', 'day'=>'Mon,Tue,Wed,Thu', 'start_time'=>'08:00', 'finish_time'=>'16:00', 'min_price'=>120, 'hours_inc'=>2,'overtime_cost'=>15,'overtime_interval'=>'30', 'max_price'=>200, 'free_travel'=>'30', 'travel_cost'=>15,'travel_interval'=>'30','email_template_id'=>2, 'setup_time' => '30','created_at'=>$now, 'updated_at'=>$now),
            array('id'=>8,'name'=>'Primary School Disco', 'day'=>'Fri', 'start_time'=>'08:00', 'finish_time'=>'16:30', 'min_price'=>120, 'hours_inc'=>2,'overtime_cost'=>15,'overtime_interval'=>'30', 'max_price'=>200, 'free_travel'=>'30', 'travel_cost'=>15,'travel_interval'=>'30','email_template_id'=>2,'setup_time' => '30', 'created_at'=>$now, 'updated_at'=>$now),
            array('id'=>9,'name'=>'Primary School Disco', 'day'=>'Fri', 'start_time'=>'16:30', 'finish_time'=>'18:00', 'min_price'=>150, 'hours_inc'=>2,'overtime_cost'=>15,'overtime_interval'=>'30', 'max_price'=>200, 'free_travel'=>'30', 'travel_cost'=>15,'travel_interval'=>'30','email_template_id'=>2, 'setup_time' => '30','created_at'=>$now, 'updated_at'=>$now),
            array('id'=>10,'name'=>'Primary School Disco', 'day'=>'Sat', 'start_time'=>'08:00', 'finish_time'=>'16:30', 'min_price'=>120, 'hours_inc'=>2,'overtime_cost'=>15,'overtime_interval'=>'30', 'max_price'=>200, 'free_travel'=>'30', 'travel_cost'=>15,'travel_interval'=>'30','email_template_id'=>2,'setup_time' => '30', 'created_at'=>$now, 'updated_at'=>$now),
            array('id'=>11,'name'=>'Primary School Disco', 'day'=>'Sat', 'start_time'=>'16:30', 'finish_time'=>'18:00', 'min_price'=>175, 'hours_inc'=>2,'overtime_cost'=>15,'overtime_interval'=>'30', 'max_price'=>200, 'free_travel'=>'30', 'travel_cost'=>15,'travel_interval'=>'30','email_template_id'=>2,'setup_time' => '30', 'created_at'=>$now, 'updated_at'=>$now),
            array('id'=>12,'name'=>'Primary School Disco', 'day'=>'Sun', 'start_time'=>'08:00', 'finish_time'=>'16:00', 'min_price'=>120, 'hours_inc'=>2,'overtime_cost'=>15,'overtime_interval'=>'30', 'max_price'=>200, 'free_travel'=>'30', 'travel_cost'=>15,'travel_interval'=>'30','email_template_id'=>2,'setup_time' => '30', 'created_at'=>$now, 'updated_at'=>$now),
                
            array('id'=>13,'name'=>'Wedding Reception', 'day'=>'Mon,Tue,Wed,Thu', 'start_time'=>'08:00', 'finish_time'=>'16:00', 'min_price'=>250, 'hours_inc'=>5,'overtime_cost'=>25,'overtime_interval'=>'30', 'max_price'=>500, 'free_travel'=>'60', 'travel_cost'=>25,'travel_interval'=>'30','email_template_id'=>3, 'setup_time' => '45','created_at'=>$now, 'updated_at'=>$now),
            array('id'=>14,'name'=>'Wedding Reception', 'day'=>'Fri', 'start_time'=>'08:00', 'finish_time'=>'16:00', 'min_price'=>300, 'hours_inc'=>5,'overtime_cost'=>25,'overtime_interval'=>'30', 'max_price'=>500, 'free_travel'=>'60', 'travel_cost'=>25,'travel_interval'=>'30','email_template_id'=>3, 'setup_time' => '45','created_at'=>$now, 'updated_at'=>$now),
            array('id'=>15,'name'=>'Wedding Reception', 'day'=>'Sat', 'start_time'=>'08:00', 'finish_time'=>'16:00', 'min_price'=>300, 'hours_inc'=>5,'overtime_cost'=>25,'overtime_interval'=>'30', 'max_price'=>500, 'free_travel'=>'0', 'travel_cost'=>50,'travel_interval'=>'60','email_template_id'=>3,'setup_time' => '60', 'created_at'=>$now, 'updated_at'=>$now),
            array('id'=>16,'name'=>'Wedding Reception', 'day'=>'Sun', 'start_time'=>'08:00', 'finish_time'=>'16:00', 'min_price'=>250, 'hours_inc'=>5,'overtime_cost'=>25,'overtime_interval'=>'30', 'max_price'=>500, 'free_travel'=>'60', 'travel_cost'=>25,'travel_interval'=>'30','email_template_id'=>3,'setup_time' => '45', 'created_at'=>$now, 'updated_at'=>$now),
                
            array('id'=>17,'name'=>'Others', 'day'=>'Mon,Tue,Wed,Thu', 'start_time'=>'08:00', 'finish_time'=>'16:00', 'min_price'=>200, 'hours_inc'=>5,'overtime_cost'=>25,'overtime_interval'=>'30', 'max_price'=>500, 'free_travel'=>'60', 'travel_cost'=>25,'travel_interval'=>'30','email_template_id'=>4, 'setup_time' => '15','created_at'=>$now, 'updated_at'=>$now),
            array('id'=>18,'name'=>'Others', 'day'=>'Fri', 'start_time'=>'08:00', 'finish_time'=>'16:00', 'min_price'=>200, 'hours_inc'=>5,'overtime_cost'=>25,'overtime_interval'=>'30', 'max_price'=>500, 'free_travel'=>'60', 'travel_cost'=>25,'travel_interval'=>'30','email_template_id'=>4,  'setup_time' => '15','created_at'=>$now, 'updated_at'=>$now),
            array('id'=>19,'name'=>'Others', 'day'=>'Sat', 'start_time'=>'08:00', 'finish_time'=>'16:00', 'min_price'=>200, 'hours_inc'=>5,'overtime_cost'=>25,'overtime_interval'=>'30', 'max_price'=>500, 'free_travel'=>'60', 'travel_cost'=>25,'travel_interval'=>'30','email_template_id'=>4,  'setup_time' => '15','created_at'=>$now, 'updated_at'=>$now),
            array('id'=>20,'name'=>'Others', 'day'=>'Sun', 'start_time'=>'08:00', 'finish_time'=>'16:00', 'min_price'=>200, 'hours_inc'=>5,'overtime_cost'=>25,'overtime_interval'=>'30', 'max_price'=>500, 'free_travel'=>'60', 'travel_cost'=>25,'travel_interval'=>'30','email_template_id'=>4, 'setup_time' => '15', 'created_at'=>$now, 'updated_at'=>$now)
        ) );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('packages');
	}

}
