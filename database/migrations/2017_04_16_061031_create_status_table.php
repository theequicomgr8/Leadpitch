<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status', function (Blueprint $table) {
            $table->increments('id');
			$table->string('name')->unique();
			$table->tinyInteger('show_exp_date')->default(1);
			$table->tinyInteger('lead_filter')->default(1);
			$table->tinyInteger('lead_follow_up')->default(1);
			$table->tinyInteger('add_demo')->default(1);
			$table->tinyInteger('demo_filter')->default(1);
			$table->tinyInteger('demo_follow_up')->default(1);
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
        Schema::drop('status');
    }
}
