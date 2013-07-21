<?PHP 

/******************************************************************************
 *                                                                            *
 * Copyright (c) 1999-2008  Wimba, All Rights Reserved.                       *
 *                                                                            *
 * COPYRIGHT:                                                                 *
 *      This software is the property of Wimba.                               *
 *      You can redistribute it and/or modify it under the terms of           *
 *      the GNU General Public License as published by the                    *
 *      Free Software Foundation.                                             *
 *                                                                            *
 * WARRANTIES:                                                                *
 *      This software is distributed in the hope that it will be useful,      *
 *      but WITHOUT ANY WARRANTY; without even the implied warranty of        *
 *      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         *
 *      GNU General Public License for more details.                          *
 *                                                                            *
 *      You should have received a copy of the GNU General Public License     *
 *      along with the Wimba Moodle Integration;                              *
 *      if not, write to the Free Software Foundation, Inc.,                  *
 *      51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA                *
 *                                                                            *
 * Author: Samy Hazan                                                         *
 *                                                                            *  
 * Date: October 2006                                                         *                                                                        *
 *                                                                            *
 ******************************************************************************/


/* $Id: generateXmlMainPanel.php 76089 2009-09-01 22:22:41Z trollinger $ */

/// This page generates the xml of the principal window

global $CFG;
header( 'Content-type: application/xml' );
require_once("../../config.php");
require_once("lib.php");
require_once("lib/php/common/WimbaXml.php");
require_once("lib/php/common/WimbaCommons.php"); 
require_once("lib/php/common/WimbaUI.php");        
require_once("lib/php/common/XmlResource.php");    
require_once('lib/php/vt/WimbaVoicetools.php'); 
require_once('lib/php/vt/WimbaVoicetoolsAPI.php');  
require_once('lib/php/common/WimbaLib.php');  

if (version_compare(PHP_VERSION,'5','>=') && file_exists($CFG->dirroot . '/auth/cas/CAS/domxml-php4-php5.php')) {
   require_once($CFG->dirroot . '/auth/cas/CAS/domxml-php4-php5.php');		
} else if (version_compare(PHP_VERSION,'5','>=') ){
   require_once('lib/php/common/domxml-php4-php5.php');		
}

set_error_handler("WIMBA_manage_error");

$messageProduct=optional_param("messageProduct","", PARAM_RAW);
$messageAction=optional_param("messageAction","", PARAM_RAW);

foreach(WIMBA_getKeysOfGeneralParameters() as $param){ 
	$value=optional_param($param["value"],$param["default_value"],$param["type"]);
	if($value!=null)
		$params[$param["value"]] = $value;
}

require_login($params["enc_course_id"]);
$uiManager=new WIMBA_WimbaUI($params); 

wimba_add_log(WIMBA_DEBUG,voiceboard_LOGS,"getXmlListPanel : parameters  \n" . print_r($params,true)); 

if(isset($params["error"]))
{
    wimba_add_log(WIMBA_ERROR,voiceboard_LOGS,"getXmlListPanel : ". get_string ($params["error"], 'voiceboard')); 
 	$uiManager->WIMBA_setError( get_string( $params["error"], 'voiceboard') );
}
else if (!voicetools_api_isConfigured()) {
    $uiManager->WIMBA_setError(get_string ('error_unconfigured_vt', 'voiceboard'));
} else {
  //Session Management 	
    if( $uiManager->WIMBA_getSessionError() === false )//good
    { 
        $message="";
        if( !empty($messageProduct) && !empty($messageAction) )
        {
        	$message = get_string( "message_".$messageProduct."_start", "voiceboard")."  ".
        	           get_string( "message_".$messageAction."_end", "voiceboard" );
        }
        $uiManager->WIMBA_getVTPrincipalView($message,"board"); 
    }
    else
    { //bad session	
        wimba_add_log(WIMBA_ERROR,voiceboard_LOGS,"getXmlListPanel : ". get_string ('error_session', 'voiceboard')); 
        $uiManager->WIMBA_setError(get_string ('error_session', 'voiceboard'));
    }
}

wimba_add_log(WIMBA_DEBUG,voiceboard_LOGS,"getXmlListPanel : xml generated \n". $uiManager->WIMBA_getXmlString()); 

if(isset($error_wimba))//error fatal was detected
{
    $uiManager->WIMBA_setError(get_string ('error_display', 'voiceboard'));
}

echo $uiManager->WIMBA_getXmlString();

?>
