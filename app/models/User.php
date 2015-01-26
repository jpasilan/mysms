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
  
  public function smsCredit()
  {
    return $this->hasOne('SmsCredit', 'id');  
  }
 
  public function canPurchaseCredits()
  {
    return $this->smsCredit && ($this->smsCredit->purchases_left > 0);  
  }
  
  public function hasSmsCredits()
  {
    return $this->getSmsCredits() > 0;
  }
  
  public function getSmsCredits()
  {
    return $this->smsCredit ? $this->smsCredit->credits : 0;
  }
  
  public function deductSmsCredit($count)
  {
    $currentCredits = $this->getSmsCredits();
    
    $this->smsCredit()->update([
      'credits' => $currentCredits - $count
    ]);
  }
  
  public function getFullNameAttribute()
  {
    return Str::title($this->first_name . ' ' . $this->last_name);
  }

}
