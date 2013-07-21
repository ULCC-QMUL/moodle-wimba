function hideCalendarEvent(value)
{
  if($("id_calendar_event").checked==true) {
    value="visible";
  } else {
    value="hidden";
  }

  $("calendar").style.visibility=value ;
  $("calendar_extra").style.visibility=value ;
}

YUI().use('yui2-event', 'yui2-yahoo', function(Y) {
  var YAHOO = Y.YUI2;
  YAHOO.util.Event.onDOMReady(hideCalendarEvent("check"));
});
