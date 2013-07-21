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
 * Author: Hugues Pisapia                                                     *
 *                                                                            *
 *                                                                            *
 ******************************************************************************/
require_once ('../../config.php');
require_once ('lib/php/common/WimbaLib.php');
global $CFG;

//Gets the parameters
 $action = required_param("action", PARAM_ACTION);
 $log = optional_param("log", null, PARAM_TEXT);
 define("WC", "liveclassroom");
 
 //CVMI-137
 require_login();
 
//Make sure wea re an admin
if (!is_siteadmin()) {
	error("Invalid user");
}

  //Set the log file name.
 if (isset($log)){
    $file = WIMBA_DIR."/".$log;
 }

 /**If action is list, lists all the logs in the level corresponding folder
  * If action is download, download the selected log
  */
    if($action == "list"){
        require_once("./loglist.php");
    }
    if (isset($file)){
        $ret = preg_match("/^".str_replace("/","\/",WIMBA_DIR)."/",realpath($file));

        if ($action == "download" && $ret){
            header("Content-type: application/octet-stream" );
            header("Content-Disposition: attachment; filename=".$log);
            readfile ($file);
        } else if ($action == "download" && !$ret) {
            wimba_add_log(WIMBA_ERROR,WC,"Invalid file requested: ".$file);
            error("Invalid file requested");
        }
    }

?>
