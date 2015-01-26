@extends('layouts.master')

@section('content')
<div class="row">
  <div class="col-md-12">
    <h2>Welcome {{ $user->full_name }}</h2>
    <div class="row">
      <div class="col-md-4">
        <div class="panel panel-default">
          <div class="panel-heading">SMS Credits</div>
          <div class="panel-body">
            <p>You have <strong>{{ $user->getSmsCredits() }}</strong> SMS credits left. You can purchase 5 more by clicking on the button below.</p>
            <div class="row text-center">
              <div class="col-md-12">
                {{ Form::open(['url' => 'sms/purchase', 'id' => 'buy-credits']) }}
                  {{ Form::hidden('sms_credits', null, ['id' => 'sms_credits']) }}
                  {{ Form::hidden('stripe_email', null, ['id' => 'stripe_email']) }}
                  {{ Form::hidden('stripe_token', null, ['id' => 'stripe_token']) }}
                  <button type="submit" class="stripe-button-el"{{ ! $user->canPurchaseCredits() ? ' disabled' : '' }}>
                    <span style="display:block; min-height: 30px;">Buy 5 SMS Credits</span>
                  </button>
                {{ Form::close() }}
              </div>
            </div>
            <div class="row" style="margin-top: 10px">
              <div class="col-md-12">
                <small>
                  <strong>Note:</strong> The button will be disabled after trying it out twice. Also, the purchase function is in test mode so this will not work with real credit card numbers. Instead, use the following for testing:
                  <ul class="list-unstyled" style="margin-top: 10px">
                    <li><strong>Card Number:</strong> 4242 4242 4242 4242</li>
                    <li><strong>CVC:</strong> any 3 digit number</li>
                    <li><strong>Month and Year:</strong> any future month and year</li>
                  </ul>
                </small>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">          
          <div class="panel panel-default">
            <div class="panel-heading">Send SMS Message</div>
            <div class="panel-body">
              {{ Form::open(['url' => 'sms/send', 'id' => 'send-sms']) }}
                <div class="form-group">
                  {{ Form::label('mobile_from', 'From') }}
                  <?php $twilioTestNumbers = ['+15005550001', '+15005550007', '+15005550008', '+15005550006'] ?>
                  {{ Form::select('mobile_from', ['' => 'Select Phone Number'] + array_combine($twilioTestNumbers, $twilioTestNumbers), '', [
                      'id' => 'mobile_from', 
                      'class' => 'form-control input-md', 
                      'required' => true,
                  ]) }}
					      </div>
                <div class="form-group">
                {{ Form::label('mobile_to', 'To')}}
						    {{ Form::text('mobile_to', '', 
                    [
                      'id' => 'mobile_to', 
                      'class' => 'form-control input-md',
                      'required' => true,
                  ]) }}
					      </div>
                <div class="form-group">
                {{ Form::label('sms_message', 'Message', ['class' => 'sms-message']) }}
						    {{ Form::textarea('sms_message', '', 
                    [
                      'id' => 'sms_message', 
                      'class' => 'form-control input-md', 
                      'required' => true
                  ]) }}
					      </div>
                <button class="btn btn-lg btn-primary btn-block" type="submit"{{ ! $user->hasSmsCredits() ? ' disabled' : ''}}>
                  Send
                </button>
              {{ Form::close() }}
            </div>
          </div>
        </div>
    </div>
  </div>
</div>
@stop

@section('more-footer-scripts')
{{ HTML::script('https://checkout.stripe.com/checkout.js') }}
<script type="text/javascript">
  jQuery(function() {
    // Initialize SMS related variables.
    var smsForm = jQuery('#send-sms'),
        smsFormButton = smsForm.find('button[type="submit"]');
    
    // Bind the submit event to the SMS form.
    smsForm.submit(function(e) {
      e.preventDefault();
      
      // Disable the submit button, then submit the form.
      smsFormButton.prop('disabled', true);
      smsForm.get(0).submit();
    });
    
    // Initialize Stripe related variables.
    var stripeForm = jQuery('#buy-credits'),
        stripeFormButton = stripeForm.find('button[type="submit"]'),
        allowedCredits = 5;
         
    // Configure Stripe checkout.
    var handler = StripeCheckout.configure({
      key: "{{ Config::get('services.stripe.public') }}",
      allowRememberMe: false,
      token: function(token) {
        // Set the required elements' values.
        jQuery('#stripe_token').val(token.id);
        jQuery('#stripe_email').val(token.email);
        jQuery('#sms_credits').val(allowedCredits);

        // Finally, submit the form.
        stripeFormButton.prop('disabled', true);
        stripeForm.get(0).submit();
      }
    });

    // Bind the submit event of the purchase bid credit form.
    stripeForm.submit(function(e) {
      e.preventDefault();

      handler.open({
        name: "SMS Test Site",
        description: "Buy 5 SMS credits",
        amount: (allowedCredits*2) // This should equate to 10 cents. That is, 2 cents per credit.
      });
    });
  });  
</script>
@stop