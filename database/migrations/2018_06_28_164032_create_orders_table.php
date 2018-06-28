<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
			//Client's personal data
			$table->string('fullname');
            $table->string('email');
            $table->string('address')->nullable();
			$table->string('city')->nullable();
			$table->string('state')->nullable();
			$table->string('country')->nullable();
			$table->string('zip')->nullable();
			$table->string('phone')->nullable();
			//Products data
			$table->string('items')->nullable(); //Корректно ли JSON вставится в строку?
			$table->double('subtotal')->nullable();
			$table->string('discount')->nullable();
			$table->double('afterdiscount')->nullable();
			$table->double('taxvalue')->nullable();
			$table->double('total')->nullable();
			
			$table->string('order_status')->nullable();
			
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
        Schema::dropIfExists('orders');
    }
}
