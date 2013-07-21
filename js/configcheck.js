function lc_testServerConfiguration(url, servername, username, password) {
    var aurl = url+"?user="+username+"&pass="+password+"&server="+servername;
    var callback = {
        success: function(o) {
            if (o.responseText != "ok")
                alert(o.responseText);
            else
                document.forms.adminsettings.submit();
        },
        failure: function(o) { }
    }

    YUI().use('yui2-connection', function(Y) {
      var YAHOO = Y.YUI2;
      var transaction = YAHOO.util.Connect.asyncRequest('GET', aurl, callback, null);
    });
}

function lc_CheckConfiguration(){
    serverName = document.forms.adminsettings.s__liveclassroom_servername;
    adminUserName = document.forms.adminsettings.s__liveclassroom_adminusername;
    adminPassword = document.forms.adminsettings.s__liveclassroom_adminpassword;

    if(serverName.value.length==0 || serverName.value == null)
    {
        alert(M.str.liveclassroom.wrongconfigurationURLunavailable);
        return false;
    }
    if(adminUserName.value.length==0 || adminUserName.value == null)
    {
        alert(M.str.liveclassroom.emptyAdminUsername);
        return false;
    }
    if(adminPassword.value.length==0 || adminPassword.value == null)
    {
        alert(M.str.liveclassroom.emptyAdminPassword);
        return false;
    }
    if(adminUserName.value.indexOf(" ") != -1)
    {
        alert(M.str.liveclassroom.spacesAdminUsername);
        return false;
    }
    if(adminPassword.value.indexOf(" ") != -1)
    {
        alert(M.str.liveclassroom.spacesAdminPassword);
        return false;
    }
    if (serverName.value.charAt(serverName.value.length-1) == '/')
    {
        alert(M.str.liveclassroom.trailingSlash);
        return false;
    } 

    if (!serverName.value.match('http://') && !serverName.value.match('https://'))
    {
        alert(M.str.liveclassroom.trailingHttp);
        return false;
    } 
    //check if the api account filled is correct and allowed
    lc_testServerConfiguration(M.cfg.wwwroot+"/mod/liveclassroom/testConfig.php",serverName.value,adminUserName.value,adminPassword.value);
}
