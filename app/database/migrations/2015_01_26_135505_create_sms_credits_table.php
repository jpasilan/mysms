<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsCreditsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sms_credits', function(Blueprint $table)
    {
      $table->integer('id')->unsigned();
      $table->integer('credits');
      $table->integer('purchases_left')->default(1);
      
      $table->timestamps();
    });
    
    // Give out users free SMS credits.
    $users = User::all();
    if ($users) {
      foreach ($users as $user) {
        DB::table('sms_credits')->insert([
          'id'              => $user->id,
          'credits'         => 5,
          'purchases_left'  => 1,
          'created_at'      => \Carbon\Carbon::now(),
          'updated_at'      => \Carbon\Carbon::now(),
        ]);
      }
    }
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('sms_credits');
	}

}
