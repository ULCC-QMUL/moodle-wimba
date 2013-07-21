function validate(type){
  // name can't be null
  $('isfirst').value = type;
  if( isFormValidated == false)
  { 
    return false;
  }
  
  $("form").submit();
}

function isOk()
{
  if( !$("nameNewResource").value.blank())
  {
    $("advancedOk").removeClassName("regular_btn-submit-disabled");
    $("advancedOk").addClassName("regular_btn-submit");
    $("advancedOk").disabled="";
  } 
  else
  {
    $("advancedOk").addClassName("regular_btn-submit-disabled");
    $("advancedOk").removeClassName("regular_btn-submit");
    $("advancedOk").disabled="true";
  }
}

function hideCalendarEvent(value)
{
   // if(value=="check")
    //{                              
        if($("id_calendar_event").checked==true)
        {
            value="visible";
        }
        else
        {
            value="hidden";
        }
    //}      
    
    $("calendar").style.visibility=value ;
    $("calendar_extra").style.visibility=value ;
}

function create(name,courseid){
    if (name == '')
        return false;
    createNewResource(M.cfg.wwwroot+"/mod/voicepodcaster/manageAction.php","voicetools","pc",name,$F($('mform1')["url_params"]));
}

function LoadNewFeaturePopup(current)
{
    if( current == "new" ){
        var ret = prompt('Please enter a title for the new Voice Podcaster');
        var pattern=/^\s+$/g;
        if (pattern.test(ret)) {
          $('id_resource').selectedIndex=0;
          alert('Voice Podcaster name can not be blank');
          return false;
        }
        if (ret == null) {
          $('id_resource').selectedIndex=0;
          return false;
        } else {
          create(ret);
        }
    }
}

