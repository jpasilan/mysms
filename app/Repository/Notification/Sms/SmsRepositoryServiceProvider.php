<?php namespace Repository\Notification\Sms;

use Illuminate\Support\ServiceProvider;

class SmsRepositoryServiceProvider extends ServiceProvider {
  
  public function register()
  {
    $this->app->bind(
      'Repository\Notification\Sms\SmsRepositoryInterface',
      'Repository\Notification\Sms\TwilioSmsRepository'
    );
  }
  
}