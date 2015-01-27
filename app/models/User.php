<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Cartalyst\Sentry\Users\Eloquent\User implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');
  
  /**
   * HasOne relation between the User and SmsCredit.
   */
  public function smsCredit()
  {
    return $this->hasOne('SmsCredit', 'id');  
  }
 
  /**
   * Check whether user is allowed to purchase credits.
   * 
   * @return boolean
   */
  public function canPurchaseCredits()
  {
    return $this->smsCredit && ($this->smsCredit->purchases_left > 0);  
  }
  
  /**
   * Check whether user still has SMS credits left.
   *
   * @return boolean
   */
  public function hasSmsCredits()
  {
    return $this->getSmsCredits() > 0;
  }
  
  /** 
   * Get the number of SMS credits.
   * 
   * @return int
   */
  public function getSmsCredits()
  {
    return $this->smsCredit ? $this->smsCredit->credits : 0;
  }
  
  /**
   * Deduct the SMS credits by the number of $count.
   * 
   * @param   int   $count    Number of credits to deduct.
   */
  public function deductSmsCredit($count)
  {
    $currentCredits = $this->getSmsCredits();
    
    $this->smsCredit()->update([
      'credits' => $currentCredits - $count
    ]);
  }
  
  /**
   * Attribute accessor to get the user's full name.
   * 
   * @return string
   */
  public function getFullNameAttribute()
  {
    return Str::title($this->first_name . ' ' . $this->last_name);
  }

}
