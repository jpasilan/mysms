<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
    Eloquent::unguard();

		$this->call('UserTableSeeder');
    $this->command->info('User table seeded');
    
    $this->call('SmsCreditTableSeeder');
    $this->command->info('Sms Credit table seeded');
	}

}

class UserTableSeeder extends Seeder {
  
  public function run()
  {
    DB::table('users')->delete();
    
    $users = [
      ['first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com', 'password' => 'johntest'],
      ['first_name' => 'Jane', 'last_name' => 'Doe', 'email' => 'jane@example.com', 'password' => 'janetest'],
    ];
    
    foreach ($users as $user) {
      try {
        Sentry::register([
          'first_name'    => $user['first_name'],
          'last_name'     => $user['last_name'],
          'email'         => $user['email'],
          'password'      => $user['password'],
        ], true);
        
        $this->command->info("Added user: {$user['email']}.");
      } catch (Cartalyst\Sentry\Users\UserExistsException $e) {
        $this->command->info("User {$user['email']} already exists.");
      }
    }
  }
}

class SmsCreditTableSeeder extends Seeder {
  
  public function run()
  {
    DB::table('sms_credits')->delete();
    
    $users = User::all();
    if ($users) {
      foreach ($users as $user) {
        SmsCredit::create([
          'id'             => $user->id,
          'credits'        => 5,
          'purchases_left' => 2
        ]);
      }  
    }
  }
  
}