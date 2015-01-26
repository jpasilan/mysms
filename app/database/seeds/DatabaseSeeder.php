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
	}

}

class UserTableSeeder extends Seeder {
  
  public function run()
  {
    DB::table('users')->delete();
    
    $users = [
      ['first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com'],
      ['first_name' => 'Jane', 'last_name' => 'Doe', 'email' => 'jane@example.com'],
    ];
    
    foreach ($users as $user) {
      try {
        Sentry::register([
          'first_name'    => $user['first_name'],
          'last_name'     => $user['last_name'],
          'email'         => $user['email'],
          'password'      => 'test123',
        ], true);
        
        $this->command->info("Added user: {$user['email']}.");
      } catch (Cartalyst\Sentry\Users\UserExistsException $e) {
        $this->command->info("User {$user['email']} already exists.");
      }
    }
  }
}