<?php namespace Repository\Payment;

use Illuminate\Support\ServiceProvider;

class PaymentRepositoryServiceProvider extends ServiceProvider {
  
  public function register()
  {
    $this->app->bind(
      'Repository\Payment\PaymentRepositoryInterface',
      'Repository\Payment\StripePaymentRepository'
    );
  }
  
}