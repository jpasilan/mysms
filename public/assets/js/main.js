var max_sms_characters = 160;

var setCharacterCounter = function(el, counterEl) {
  var text_area = jQuery(el),
      text_area_counter = jQuery(counterEl),
      limit = max_sms_characters;
  
   var text = text_area.val(),
       text_length = parseInt(text.length),
       count_diff = limit - text_length;
  
  if (count_diff <= 0) {
    text_area.val(text.substring(0, limit));
  }
  
  text_area_counter.html(count_diff);
}

jQuery(function(){
  jQuery('label.sms-message').append('&nbsp;<small>(<span id="sms_message_count">' + max_sms_characters + '</span> characters left)</small>');
  setCharacterCounter('#sms_message', '#sms_message_count');
});

jQuery('#sms_message').bind('textchange', function(e, previousText) {
  setCharacterCounter('#sms_message', '#sms_message_count');
});