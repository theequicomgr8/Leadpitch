<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDemoFollowUpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demo_follow_ups', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->integer('status')->unsigned();
			$table->dateTime('expected_date_time')->nullable();
			$table->text('remark');
			$table->bigInteger('demo_id')->unsigned();
            $table->timestamps();
			$table->foreign('demo_id')->references('id')->on('demos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('demo_follow_ups');
    }
}
