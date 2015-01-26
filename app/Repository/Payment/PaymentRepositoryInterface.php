<?php namespace Repository\Payment;

interface PaymentRepositoryInterface {
  
  public function charge(array $data, $apiKey = '');
  
}