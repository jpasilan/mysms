<?php namespace Repository\Payment;

interface PaymentRepositoryInterface {
  
  /**
   * Execute the payment or charge.
   * 
   * @param array   $data       Payment data
   * @param string  $apiKey     API key.
   * @return array
   */
  public function charge(array $data, $apiKey = '');
  
}