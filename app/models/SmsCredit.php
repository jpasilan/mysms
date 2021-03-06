<?php

class SmsCredit extends Eloquent {
  
  /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'sms_credits';
  
  /**
   * Fillable attributes.
   *
   * @var array
   */
  protected $fillable = ['id', 'credits', 'purchases_left'];

  /**
   * HasOne relation between SmsCredit and User.
   */
  public function user()
  {
    return $this->belongsTo('User', 'id');      
  }

}