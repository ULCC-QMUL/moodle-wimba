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

function popupCancel()
{
    $("popup").style.display="none";
    $("hiddenDiv").style.display="none";
    location.href = M.cfg.wwwroot+"/course/view.php?id="+$F($('mform1')['course']);
}

function popupOk()
{
    $("popup").style.display="none";
    $("hiddenDiv").style.display="none";
    location.href = M.cfg.wwwroot+"/mod/liveclassroom/index.php?id="+$F($('mform1')['course']);
}

function create(name,courseid){
    if(name == '')
        return false;
    $("newPopup").hide();
    $('loading').show();
    createNewResource(M.cfg.wwwroot+"/mod/liveclassroom/manageAction.php","liveclassroom","",name,$F($('mform1')["url_params"]));
    name=""; //for the next on
    $('name').focus();
}

function onCancelButtonPopup(){
    $('id_resource').selectedIndex=0;
    $('newPopup').hide();
    $('hiddenDiv').hide();
    $('name').focus();
    var allSelect =  document.getElementsByTagName("select");
    for( i=0;i<allSelect.length;i++)
    {
        allSelect[i].style.visibility="";
    }
    return false;
}

function LoadNewFeaturePopup(current)
{
  if ( current == "new" ){
    var ret = prompt('Please enter a title for the new room');
    var longnamePattern = /^[a-z|A-Z|_]+[a-z|A-Z|0-9|_|\s| |\'|!|?|(|)|@|:|\-|\/|]{1,50}$/;
    var spacesPattern=/^\s+$/g;

    if (!longnamePattern.test(ret)) {
      alert("Please fill in a Title that is 1-50 alphanumeric or space characters or - / : ' ? ! ( ) @ and begins with a letter or _\n");
      return false;
    }

    if (spacesPattern.test(ret)) {
      alert("The Title must contain alphanumeric characters or - / : ' ? ! ( )\n");
      return false;
    }

    if (ret > 50) {
      alert("The Title you have entered is too long. This field should not exceed 50 characters.\n");
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

