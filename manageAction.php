<?php

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
 * Author: Hazan Samy                                                         *
 *                                                                            *
 * Date: September 2006                                                       *
 *                                                                            *
 ******************************************************************************/
 

/* $Id: manageAction.php 80709 2010-11-17 14:12:11Z bdrust $ */

/* This page manage the action create, update, delete for a room */
global $CFG;
require_once ("../../config.php");
require_once ("lib.php");
require_once ("lib/php/lc/LCAction.php");
require_once ("lib/php/common/WimbaCommons.php");
require_once ("lib/php/common/WimbaLib.php");
require_once ("lib/php/common/WimbaUI.php");
require_once ("lib/php/common/XmlRoom.php");
require_once ("lib/php/common/WimbaXml.php");

if (version_compare(PHP_VERSION,'5','>=') && file_exists($CFG->dirroot . '/auth/cas/CAS/domxml-php4-php5.php')) {
   require_once($CFG->dirroot . '/auth/cas/CAS/domxml-php4-php5.php');		
} else if (version_compare(PHP_VERSION,'5','>=')){
   require_once('lib/php/common/domxml-php4-php5.php');		
}  

$keys = array_merge(WIMBA_getKeysOfGeneralParameters(),WIMBA_getKeyWimbaClassroomForm());

$params=array();
foreach($keys as $param)
{	
    $value = optional_param($param["value"], $param["default_value"], $param["type"]);
    $params[$param["value"]] = $value;
}

require_login($params["enc_course_id"]);
$action = $params["action"];
$roomId = $params["resource_id"];
$rid_audio = $params["rid_audio"];
$session = new WIMBA_WimbaMoodleSession($params);
$xml = new WIMBA_WimbaXml();

if ( $session->error === false && $session !=  NULL ) {

    $api = new WIMBA_LCAction($session, 
                        $CFG->liveclassroom_servername, 
                        $CFG->liveclassroom_adminusername, 
                        $CFG->liveclassroom_adminpassword, 
                        $CFG->dataroot);
                        
	$prefix = $api->WIMBA_getPrefix();

	switch ( $action ) {

		case "launch" :
			$roomId = required_param( 'resource_id', PARAM_SAFEDIR );
			
			if ($params["studentView"] == "true") {
				$authToken = $api->WIMBA_getAuthokenNormal($session->WIMBA_getCourseId()."_S",
                                    				 $session->WIMBA_getFirstname(),
                                    				 $session->WIMBA_getLastname());
			} else {
				$authToken = $api->WIMBA_getAuthoken();
			}
			WIMBA_redirection( $CFG->liveclassroom_servername.'/check_wizard.pl?'.
                                			'channel='.$api->WIMBA_getPrefix().$roomId.
                                			'&hzA='.$authToken.'&'.$api->api->WIMBA_get_bridge_header_string() );
			break;
			
		case "create" :
			$id = $api->WIMBA_createRoom($roomId, "false");
			$messageAction = "created";
			$messageProduct = "room";
			break;
			
		case "createDefault" :
			$id = $api->WIMBA_createSimpleRoom($params["longname"], "true", $params["enc_course_id"]);
			echo $prefix.$id;
			exit();
			break;	
            
		case "update" :
			$id = $api->WIMBA_createRoom($roomId, "true");
			$messageAction = "updated";
			$messageProduct = "room";
			break;
			
		case "delete" :
            
		    $id = $api->WIMBA_deleteRoom($roomId);
			//delte the activity linked to this room
			$prefix = $api->WIMBA_getPrefix();
			if ( !liveclassroom_delete_all_instance_of_room($roomId,$prefix) ) 
			{
				notify("Could not delete the activities for the room: $roomId");
			}
			$messageAction = "deleted";
			$messageProduct = "room";
			break;
			
		case "openContent" :
			$authToken = $api->WIMBA_getAuthoken();
			WIMBA_redirection( $CFG->liveclassroom_servername.'/admin/class/carousels.epl?'.
                                			'class_id='.$api->WIMBA_getPrefix().$roomId. 
                                			'&hzA='.$authToken. 
                                			'&no_sidebar=1&'.$api->api->WIMBA_get_bridge_header_string());
			break;
			
		case "openReport" :
			$authToken = $api->WIMBA_getAuthoken();
			WIMBA_redirection( 'reports.php?id='.$roomId.'&hzA='.$authToken.'&courseId='.$session->WIMBA_getCourseId() );
			exit ();
			break;
			
		case "openAdvancedMedia" :
			$authToken = $api->WIMBA_getAuthoken();
			WIMBA_redirection ( $CFG->liveclassroom_servername.'/admin/api/class/media.pl?'.
                                			'class_id='.$api->WIMBA_getPrefix().$roomId. 
                                			'&hzA='.$authToken. 
                                			'&no_sidebar=1&'.$api->api->WIMBA_get_bridge_header_string());
			exit ();
			break;
			
		case "openAdvancedRoom" :
			$authToken = $api->WIMBA_getAuthoken();
			WIMBA_redirection ( $CFG->liveclassroom_servername.'/admin/api/class/properties.pl?'.
                                			'class_id='.$api->WIMBA_getPrefix().$roomId. 
                                			'&hzA='.$authToken. 
                                			'&no_sidebar=1&'.$api->api->WIMBA_get_bridge_header_string());
			break;
			
		case "getDialInformation" :
                        header( 'Content-type: application/xml' );
			$select_room = $api->WIMBA_getRoom($roomId);

			if ( $params["studentView"] == "true" || $session->WIMBA_isInstructor() === false )
			{
				$xml->WIMBA_createPopupDialElement(get_string("popup_dial_title", "liveclassroom"), 
                            				 get_string("popup_dial_numbers", "liveclassroom"), 
                            				 get_string("popup_dial_pin", "liveclassroom"),
                            				 null, 
                            				 $select_room->WIMBA_getParticipantPin(), 
                            				 $api->WIMBA_getPhoneNumbers());
			}
			else
			{
				$xml->WIMBA_createPopupDialElement(get_string("popup_dial_title", "liveclassroom"), 
                            				 get_string("popup_dial_numbers", "liveclassroom"), 
                            				 get_string("popup_dial_pin", "liveclassroom"), 
                            				 $select_room->WIMBA_getPresenterPin(), 
                            				 $select_room->WIMBA_getParticipantPin(), 
                            				 $api->WIMBA_getPhoneNumbers());
			}
            echo $xml->WIMBA_getXml();
			break;
			
		case "saveSettings" :
			$id=$api->WIMBA_createRoom($roomId, "true");
			echo "good";
            exit ();
			break;
		case "getMp3Status" :
		    $audioFileStatus=$api->WIMBA_getMp3Status($rid_audio);
		    if($audioFileStatus === false || $audioFileStatus->WIMBA_getStatus() == "" )
		    {
		      echo "error_server";
		    }
		    else
		    {
		      echo $audioFileStatus->WIMBA_getStatus().";".$audioFileStatus->WIMBA_getUri().";";
		    }
		    exit();
			break;
		case "getMp4Status" :
		    $audioFileStatus=$api->WIMBA_getMp4Status($rid_audio);
		    if($audioFileStatus === false || $audioFileStatus->WIMBA_getStatus() == "")
		    {
		     echo "error_server";
		    }
		    else
		    {
		      echo $audioFileStatus->WIMBA_getStatus().";".$audioFileStatus->WIMBA_getUri().";";
		    }
			exit();
			break;
	}

	if ($action !=  "getDialInformation") 
	{

		WIMBA_redirection ('welcome.php?'.
                            'id=' . $session->WIMBA_getCourseId() . 
                            '&' . $session->url_params . 
                            '&time=' . $session->timeOfLoad .
                            '&messageAction=' . $messageAction . 
                            '&messageProduct=' . $messageProduct);
    }
}
else
{
	WIMBA_redirection ('welcome.php?'.
                    	'id=' . $params["enc_course_id"] . 
                    	'&' . liveclassroom_WIMBA_get_url_params($params["enc_course_id"]) .
                    	'&time=' . $session->timeOfLoad .
                    	'&error=session');
}
?>
