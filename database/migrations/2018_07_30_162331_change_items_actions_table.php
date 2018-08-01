<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeItemsActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items_actions', function(Blueprint $table) {
			//Add new columns
			$table->boolean('firstweek')->default(false);
			$table->boolean('secondweek')->default(false);
			$table->boolean('thirdweek')->default(false);
			$table->boolean('fourthweek')->default(false);
		});
		
		Schema::table('items_actions', function(Blueprint $table) {
			//Drop un-needed column
			$table->dropColumn('is_visible');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		//SQLite bad feature - dropping columns need separately transactions
		Schema::table('items_actions', function($table) {
			//Delete new column
			//$table->dropColumn('firstweek');
		});
		
		Schema::table('items_actions', function($table) {
			//Delete new column
			$table->dropColumn('secondweek');
		});
		
		Schema::table('items_actions', function($table) {
			//Delete new column
			$table->dropColumn('thirdweek');
		});
		
		Schema::table('items_actions', function($table) {
			//Delete new column
			$table->dropColumn('fourthweek');
		});
	
	
		Schema::table('items_actions', function($table) {
			//Return un-needed column
			$table->boolean('is_visible')->default(false);
		});
    }
}
