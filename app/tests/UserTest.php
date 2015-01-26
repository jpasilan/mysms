<?php

class UserTest extends TestCase {
  
  public function testCanViewLoginPage()
  {
    $crawler = $this->client->request('GET', '/');
    
    $this->assertResponseOk();
    
    $h2 = $crawler->filter('h2:contains("MySMS Login")');
    $this->assertCount(1, $h2);
  }
  
  public function testCanViewDashboard()
  {
    Route::enableFilters();
    
    Sentry::shouldReceive('check')
      ->andReturn(true);
    
    Sentry::shouldReceive('getUser')
      ->andReturn(Mockery::mock('User'));
    
    View::shouldReceive('make')
      ->with('users.dashboard', Mockery::hasKey('user'))
      ->once();
    
    $crawler = $this->client->request('GET', 'dashboard');
    
    $this->assertResponseOk();
  }
  
}