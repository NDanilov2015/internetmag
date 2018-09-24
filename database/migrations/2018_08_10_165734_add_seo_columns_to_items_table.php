<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSeoColumnsToItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function(Blueprint $table) {
			$table->string('meta_title')->after('item_status')->nullable();
			$table->string('meta_keywords')->after('meta_title')->nullable();
			$table->string('meta_description')->after('meta_description')->nullable();
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
		Schema::table('items', function($table) {
			//Delete new column
			$table->dropColumn('meta_description');
		});
		
		Schema::table('items', function($table) {
			//Delete new column
			$table->dropColumn('meta_keywords');
		});
		
		Schema::table('items', function($table) {
			//Delete new column
			$table->dropColumn('meta_title');
		});
    }
}
