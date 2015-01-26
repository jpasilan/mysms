<?php

use Repository\Notification\Sms\SmsRepositoryInterface;
use Repository\Payment\PaymentRepositoryInterface;

class SmsController extends BaseController {
  
  protected $sms;
  
  protected $payment;
  
  public function __construct(SmsRepositoryInterface $sms, PaymentRepositoryInterface $payment)
  {
    $this->sms = $sms;
    $this->payment = $payment;
  }
  
  public function sendMessage()
  {
    $message = ['danger' => 'An error has occurred. Please try again.'];
    
    if (Request::isMethod('post')) {
      $rules = [
        'mobile_from' => 'required',
        'mobile_to'   => 'required',
        'sms_message' => 'required|max:160',
      ];
      
      $validator = Validator::make(Input::all(), $rules);
      if ($validator->passes()) {
        $user = Sentry::getUser();
        $status = $this->sms->sendMessage($user->id, Input::get('mobile_from'), Input::get('mobile_to'), Input::get('sms_message'));
        
        if ('success' == $status['status']) {
          $message = ['success' => $status['message']];
        } else {
          $message = ['danger' => $status['message']];
          
          return Redirect::to('dashboard')
            ->withMessage($message)
            ->withInput();
        }
      } else {
        return Redirect::to('dashboard')
          ->withMessage($message)
          ->withErrors($validator)
          ->withInput();
      }
    }
    
    return Redirect::to('dashboard')->withMessage($message);
  }
  
  public function purchaseSmsCredits()
  {
    $message = ['danger' => 'An error has occurred. Please try again.'];

    if (Request::isMethod('post')) {
      $user = Sentry::getUser();
      $credits = Input::get('sms_credits');
      $email = Input::get('stripe_email');

      if ($user->email != $email) {
        $message = ['danger' => "Payment not processed. Please provide your account's email address."];
      } else if ($credits > 5) {
        $message = ['danger' => "You are only allowed to purchase 5 SMS credits."];
      } else {
        // Get the credit card details through a token.
        $token = Input::get('stripe_token');

        // Execute the charge.
        $status = $this->payment->chargeByToken(
          $token,
          $credits * 2, // @TODO: This should not be hard-coded.
          "Payment made by {$email} for the purchase of 5 SMS credits."
        );

        // Check if the charge is successful or not.
        if ('success' == $status['status']) {
          // Add SMS credits.
          if ($user->smsCredit) {
            $user->smsCredit()->update([
              'credits'         => $credits + $user->smsCredit->credits,
              'purchases_left'  => --$user->smsCredit->purchases_left
            ]);
          } else {
            $smsCredit = [
              'id'        => $user->id,
              'credits'   => $credits,
            ];
            SmsCredit::create($smsCredit);
          }

          $message = ['success' => $status['message'] .
                      " " . Str::plural("{$credits} SMS credit", $credits) .
                      " have been added to your account"];
        } else {
          $message = ['danger' => $status['message']];
        }
      }
    }

    return Redirect::to('dashboard')->withMessage($message);
  }
  
}