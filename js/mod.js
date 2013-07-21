YUI().use('yui2-dom', function(Y) {
  var YAHOO = Y.YUI2;
  var $ = YAHOO.util.Dom.get;
  function validate(type) {
    // name can't be null
    $('isfirst').value = type;
  
    if( isFormValidated == false) { 
      return false;
    }
  
    if ($("pre_filled_subject_yes").checked && $("subject").value.blank()) {
      if (!confirm("The subject field is blank. Do you wish to continue?")) {
        return false;
      }
    }
    $("form").submit();
  }
});
