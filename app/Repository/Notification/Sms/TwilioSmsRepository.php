<?php namespace Repository\Notification\Sms;

use Illuminate\Support\Facades\Config;
use Services_Twilio, Services_Twilio_RestException;
use User, Exception;

class TwilioSmsRepository implements SmsRepositoryInterface {
  
  protected $client;
  
  public function __construct()
  {
    $this->client = $this->authenticate();
  }
  
  private function authenticate()
  {
    $sid = Config::get('services.twilio.sid');
    $token = Config::get('services.twilio.token');
    
    return new Services_Twilio($sid, $token);
  }
  
  public function sendMessage($userId, $from, $to, $message)
  {
    $user = User::find($userId);
    if ( ! $user) {
      throw new Exception("User #{$userId} does not exist.");
    }
    
    try {
      if ($user->hasSmsCredits()) {
        $response = $this->client->account->messages->sendMessage($from, $to, $message);
        
        // Deduct user's SMS credits.
        $user->deductSmsCredit(1);
        
        $message = [
          'status'   => 'success',
          'message'  => 'SMS message successfully sent.'
        ];
      } else {
        $message = [
          'status'   => 'error',
          'message'  => "You have not SMS credits left."
        ];        
      }
    } catch (Services_Twilio_RestException $e) {
      $message = [
        'status'  => 'error',
        'message' => $e->getMessage()
      ];
    }
    
    return $message;
  }
  
}