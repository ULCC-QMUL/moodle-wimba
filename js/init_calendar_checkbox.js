function hideCalendarEvent(value) {
  if($("id_calendar_event").checked==true) {
    value="visible";
  } else {
    value="hidden";
  }

  $("calendar").style.visibility=value ;
  $("calendar_extra").style.visibility=value ;
}

function enableSubject() {
  if ($("id_pre_filled_subject_false").checked==true) {
    $("id_subject").disabled = true;
    $("id_subject").value='';
  } else {
    $("id_subject").disabled = false;
  }
}

YUI().use('yui2-event', 'yui2-yahoo', function(Y) {
  var YAHOO = Y.YUI2;
  YAHOO.util.Event.onDOMReady(function(){hideCalendarEvent("check"); enableSubject();});
});
