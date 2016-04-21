<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bills', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('num');
			$table->char('means_of_payment');
			$table->integer('payment_symbol');
			$table->char('account_number');
			$table->char('iban');
			$table->char('swift');
			$table->integer('buyer_id');
			$table->integer('supplier_id');
			$table->integer('year');
			$table->date('issue_date');
			$table->date('due_date');
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
		Schema::drop('bills');
	}

}
