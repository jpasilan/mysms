<?php namespace Repository\Notification\Sms;

interface SmsRepositoryInterface {
  
  public function sendMessage($userId, $from, $to, $message);
  
}