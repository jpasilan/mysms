<?php namespace Repository\Payment;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Stripe_Charge, Stripe_Error, Stripe_CardError;

class StripePaymentRepository implements PaymentRepositoryInterface {
  
  protected $stripeKey;
  
  public function setStripeKey($key)
  {
    $this->stripeKey = $key;
  }
  
  public function getStripeKey()
  {
    return ! empty($this->stripeKey) ? $this->stripeKey : Config::get('services.stripe.secret');  
  }
  
  public function chargeByToken($token, $amount, $description)
  {
    return $this->charge([
      'card' => $token,
      'amount' => $amount,
      'description' => $description
    ]);
  }

  public function charge(array $data, $apiKey = '')
  {
    // Setting the API key.
    $this->setStripeKey($apiKey);

    try {
      $data['amount'] *= 100; // Per Stripe, amount must be in cents.

      // Set the currency.
      $data['currency'] = 'usd';

      // Then, run the actual charge.
      Stripe_Charge::create($data, $this->getStripeKey());

      $message = [
        'status' => 'success',
        'message' => Lang::get('payment.payment_processed')
      ];
    } catch (Stripe_CardError $e) {
      $message = [
        'status'    => 'error',
        'message'   => Lang::get('payment.card_declined'),
      ];
    } catch (Stripe_Error $e) {
      // @TODO: Provide failure reasons for this and other Stripe errors.
      $message = [
        'status'    => 'error',
        'message'   => Lang::get('payment.payment_not_processed')
      ];
    } catch (Exception $e) {
      $message = [
        'status'    => 'error',
        'message'   => Lang::get('payment.payment_not_processed'),
      ];
    }

    return $message;    
  }
  
}