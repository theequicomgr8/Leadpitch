<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadFollowUpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_follow_ups', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->integer('status')->unsigned();
			$table->dateTime('expected_date_time')->nullable();
			$table->text('remark');
			$table->bigInteger('lead_id')->unsigned();
            $table->timestamps();
			$table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('lead_follow_ups');
    }
}
