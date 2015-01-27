<?php namespace Repository\Notification\Sms;

interface SmsRepositoryInterface {
  
  /**
   * Send an SMS message.
   * 
   * @param int     $userId     User ID
   * @param string  $from       "From" mobile number
   * @param string  $to         "To" mobile number
   * @param string  $message    SMS message
   * @return array
   * @throws Exception
   */
  public function sendMessage($userId, $from, $to, $message);
  
}