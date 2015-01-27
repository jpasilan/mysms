<?php namespace Repository\Payment;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Stripe_Charge, Stripe_Error, Stripe_CardError;

class StripePaymentRepository implements PaymentRepositoryInterface {
  
  /**
   * @var Stripe API key.
   */
  protected $stripeKey;
  
  /**
   * Set the Stripe API key.
   * 
   * @param string  $key    API key
   */
  public function setStripeKey($key)
  {
    $this->stripeKey = $key;
  }
  
  /**
   * Get the Stripe API key.
   */
  public function getStripeKey()
  {
    return ! empty($this->stripeKey) ? $this->stripeKey : Config::get('services.stripe.secret');  
  }
  
  /**
   * Run the charge by card token.
   * 
   * @param string  $token        Card token.
   * @param float   $amount       Amount to charge.
   * @param string  $description  Charge description.
   */
  public function chargeByToken($token, $amount, $description)
  {
    return $this->charge([
      'card' => $token,
      'amount' => $amount,
      'description' => $description
    ]);
  }

  /**
   * Execute the payment or charge.
   * 
   * @param array   $data       Payment data
   * @param string  $apiKey     API key.
   * @return array
   */
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