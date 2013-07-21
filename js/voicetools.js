function WIMBA_testServerConfiguration(url, servername, username, password) {
    
    var aurl = url+"?user="+username+"&pass="+password+"&server="+servername;
    var callback = {
        success: function(o) {
            if (o.responseText != "ok" && o.responseText != '')
                alert(o.responseText);
            else if (o.responseText == '')
                alert(M.str.voicetools.wrongconfigurationURLunavailable);
            else
            	alert('Configuration Verified.');
                document.forms.adminsettings.submit();
        },
        failure: function(o) { }
    }

    YUI().use('yui2-connection', function(Y) {
      var YAHOO = Y.YUI2;
      var transaction = YAHOO.util.Connect.asyncRequest('GET', aurl, callback, null);
    });
}

function WIMBA_runPatch(url) {
    var callback = {
        success: function(o) {
            alert(o.responseText);
            window.location.href=M.cfg.wwwroot+"/admin/settings.php?section=modsettingvoicetools";
        },
        failure: function(o) { }
    }

    var transaction = YAHOO.util.Connect.asyncRequest('GET', url, callback, null);
}

function WIMBA_CheckConfiguration() {  
    serverName = document.forms.adminsettings.s__voicetools_servername;
    adminUserName = document.forms.adminsettings.s__voicetools_adminusername;
    adminPassword = document.forms.adminsettings.s__voicetools_adminpassword;
     
    if(serverName.value.length==0 || serverName.value == null)
    {
        alert(M.str.voicetools.wrongconfigurationURLunavailable);
        return false;
    }
    if(adminUserName.value.length==0 || adminUserName.value == null)
    {
        alert(M.str.voicetools.emptyAdminUsername);
        return false;
    }
    if(adminPassword.value.length==0 || adminPassword.value == null)
    {
        alert(M.str.voicetools.emptyAdminPassword);
        return false;
    }
    if(adminUserName.value.indexOf(" ") != -1)
    {
        alert(M.str.voicetools.spacesAdminUsername);
        return false;
    }
    if(adminPassword.value.indexOf(" ") != -1)
    {
        alert(M.str.voicetools.spacesAdminPassword);
        return false;
    }
    if (serverName.value.charAt(serverName.value.length-1) == '/') 
    {
        alert(M.str.voicetools.trailingSlash);
        return false;
    } 

    if (!serverName.value.match('http://') && !serverName.value.match('https://')) 
    {
    	alert(M.str.voicetools.trailingHttp);
    	return false;
    } 
    //check if the api account filled is correct and allowed
    WIMBA_testServerConfiguration(M.cfg.wwwroot+"/mod/voicetools/testConfig.php",serverName.value,adminUserName.value,adminPassword.value);     
}

function WIMBA_ApplyPatch() {
    WIMBA_runPatch(M.cfg.wwwroot+"/mod/voicetools/applyPatch.php?action=apply");
}

function WIMBA_ReversePatch() {
    WIMBA_runPatch(M.cfg.wwwroot+"/mod/voicetools/applyPatch.php?action=reverse");
}
