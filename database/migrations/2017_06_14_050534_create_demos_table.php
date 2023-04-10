<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDemosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demos', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->integer('lead_id')->nullable();
			$table->string('name');
			$table->string('email');
			$table->string('mobile');
			$table->integer('course')->unsigned();
			$table->string('sub_courses');
			$table->integer('status')->unsigned();
			$table->string('executive_call');
			$table->text('remarks');
			$table->integer('owner')->unsigned();
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
        Schema::drop('demos');
    }
}
